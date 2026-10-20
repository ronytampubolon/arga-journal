import { chromium } from 'playwright-core';

const BASE = 'http://127.0.0.1:8765/preview';
const browser = await chromium.launch({ channel: 'chrome', headless: true });
const context = await browser.newContext();
await context.addInitScript(() => localStorage.setItem('inkwell-theme', 'dark'));

const SELECTOR = 'body, header, footer, main, article, aside, nav, section, h1, h2, h3, blockquote, pre, table, input, textarea, button, .comment-list, .children, .entry-content';

const report = [];
for (const path of ['/index.html', '/single.html']) {
	const page = await context.newPage();
	await page.setViewportSize({ width: 1440, height: 900 });
	await page.goto(BASE + path, { waitUntil: 'networkidle' });
	await page.waitForTimeout(200);
	const res = await page.evaluate((sel) => {
		const html = document.documentElement;
		const out = {
			htmlColorScheme: getComputedStyle(html).colorScheme,
			htmlBg: getComputedStyle(html).backgroundColor,
			bodyBg: getComputedStyle(document.body).backgroundColor,
			bodyColor: getComputedStyle(document.body).color,
			lightChrome: [],
		};
		document.querySelectorAll(sel).forEach((el) => {
			const bg = getComputedStyle(el).backgroundColor;
			const m = bg.match(/rgba?\((\d+),\s*(\d+),\s*(\d+)/);
			if (!m) return;
			const [r, g, b] = [+m[1], +m[2], +m[3]];
			if (r > 230 && g > 230 && b > 230) {
				out.lightChrome.push(el.tagName.toLowerCase() + '.' + String(el.className).split(' ').slice(0, 3).join('.') + ' -> ' + bg);
			}
		});
		return out;
	}, SELECTOR);
	report.push({ path, ...res });
	await page.close();
}
await browser.close();

for (const r of report) {
	console.log('\n=== ' + r.path + ' ===');
	console.log('html color-scheme:', r.htmlColorScheme, '| html bg:', r.htmlBg);
	console.log('body bg:', r.bodyBg, '| body color:', r.bodyColor);
	console.log('light-background chrome elements:', r.lightChrome.length);
	r.lightChrome.slice(0, 20).forEach((x) => console.log('   ' + x));
}
