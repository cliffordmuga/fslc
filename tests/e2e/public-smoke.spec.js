import { test, expect } from '@playwright/test';

test('homepage loads with main landmark', async ({ page }) => {
    await page.goto('/');
    await expect(page.locator('#main-content')).toBeVisible();
    await expect(page.getByRole('navigation')).toBeVisible();
});

test('insights hub responds', async ({ page }) => {
    const response = await page.goto('/insights');
    expect(response?.ok()).toBeTruthy();
    await expect(page.locator('h1')).toBeVisible();
});

test('health endpoint returns ok', async ({ request }) => {
    const response = await request.get('/health');
    expect(response.ok()).toBeTruthy();
    const body = await response.json();
    expect(body.ok).toBe(true);
});
