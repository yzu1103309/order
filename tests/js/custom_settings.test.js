const settings = require('../../js/custom_settings.js');

describe('custom_settings.js with coverage support', () => {
    test('items should contain 18 menu items', () => {
        expect(settings.items).toHaveLength(18);
    });

    test('price should contain 18 prices', () => {
        expect(settings.price).toHaveLength(18);
    });

    test('items and price should have matching lengths', () => {
        expect(settings.items.length).toBe(settings.price.length);
    });

    test('typeCount should be 4', () => {
        expect(settings.typeCount).toBe(4);
    });

    test('eachTypeCount should match typeCount', () => {
        expect(settings.eachTypeCount).toEqual([5, 5, 4, 4]);
        expect(settings.eachTypeCount).toHaveLength(settings.typeCount);
    });

    test('sum of eachTypeCount should equal items length', () => {
        const totalCount = settings.eachTypeCount.reduce((sum, n) => sum + n, 0);
        expect(totalCount).toBe(settings.items.length);
    });

    test('tableCount should be 15', () => {
        expect(settings.tableCount).toBe(15);
    });

    test('soundFileName1 should be notify2.mp3', () => {
        expect(settings.soundFileName1).toBe('notify2.mp3');
    });

    test('soundFileName2 should be notify3.mp3', () => {
        expect(settings.soundFileName2).toBe('notify3.mp3');
    });

    test('first and last item names should be correct', () => {
        expect(settings.items[0]).toBe('紅燒牛肉麵');
        expect(settings.items[settings.items.length - 1]).toBe('鍋貼（5顆）');
    });

    test('first and last price should be correct', () => {
        expect(settings.price[0]).toBe(100);
        expect(settings.price[settings.price.length - 1]).toBe(30);
    });

    test('all prices should be numbers greater than 0', () => {
        settings.price.forEach((p) => {
            expect(typeof p).toBe('number');
            expect(p).toBeGreaterThan(0);
        });
    });
});