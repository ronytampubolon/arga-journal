import { chromium } from 'playwright-core';
import { mkdirSync } from 'node:fs';

const BASE = 'http://127.0.0.1:8765/preview';
const OUT = new URL('./shots/', import.meta.url).pathname;
mkdirSync(OUT, { recursive: true });

const viewports = [
	{ name: 'desktop', width: 1440, height: 900 },
	{ name: 'tablet', width: 768, height: 1024 },
	{ name: 'mobile', width: 390, height: 844 },
];
const pages = [
	{ name: 'home', path: '/index.html' },
	{ name: 'single', path: '/single.html' },
];

const browser = await chromium.launch({ channel: 'chrome', headless: true });

const overflowReport = [];

for (const mode of ['light', 'dark']) {
	const context = await browser.newContext();
	if (mode === 'dark') {
		await context.addInitScript(() => localStorage.setItem('inkwell-theme', 'dark'));
	}
	for (const vp of viewports) {
		const page = await context.newPage();
		await page.setViewportSize({ width: vp.width, height: vp.height });
		for (const p of pages) {
			await page.goto(BASE + p.path, { waitUntil: 'networkidle' });
			await page.waitForTimeout(150);
			const m = await page.evaluate(() => ({
				scrollWidth: document.documentElement.scrollWidth,
				innerWidth: window.innerWidth,
			}));
			if (m.scrollWidth > m.innerWidth) {
				overflowReport.push(`${mode}/${vp.name}/${p.name}: horizontal overflow ${m.scrollWidth - m.innerWidth}px`);
			}
			await page.screenshot({ path: `${OUT}${p.name}-${vp.name}-${mode}.png`, fullPage: true });
			if (p.name === 'home' && vp.name === 'mobile' && mode === 'light') {
				await page.click('#nav-toggle');
				await page.waitForTimeout(150);
				await page.screenshot({ path: `${OUT}home-mobile-menu-open.png`, fullPage: false });
			}
		}
		await page.close();
	}
	await context.close();
}

await browser.close();

if (overflowReport.length) {
	console.log('OVERFLOW DETECTED:');
	overflowReport.forEach((r) => console.log(' - ' + r));
	process.exitCode = 1;
} else {
	console.log('No horizontal overflow at any viewport.');
}
console.log('Screenshots written to preview/shots/');
