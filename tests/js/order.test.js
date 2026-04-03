global.items = ['牛肉麵', '炒飯', '湯'];
global.price = [100, 70, 30];

const htmlStore = {};
const cssStore = {};
const valStore = {};

global.$ = jest.fn((selector) => ({
    html: jest.fn((value) => {
        if (value !== undefined) htmlStore[selector] = value;
        return htmlStore[selector];
    }),
    css: jest.fn((arg1, arg2) => {
        if (arg2 !== undefined) {
            if (!cssStore[selector]) cssStore[selector] = {};
            cssStore[selector][arg1] = arg2;
            return cssStore[selector];
        }
        if (arg1 !== undefined) {
            cssStore[selector] = arg1;
            return cssStore[selector];
        }
        return cssStore[selector];
    }),
    val: jest.fn(() => valStore[selector] || '')
}));

global.$.ajax = jest.fn();

global.document = {
    getElementById: jest.fn(() => ({
        play: jest.fn()
    }))
};

global.alert = jest.fn();
global.orderPage = jest.fn();

const order = require('../../js/order');

describe('order.js unit tests', () => {
    beforeEach(() => {
        order.setState({
            itemCount: items.length,
            originD: Array(items.length).fill(0),
            dish: Array(items.length).fill(0),
            total: 0
        });

        for (const key in htmlStore) delete htmlStore[key];
        for (const key in cssStore) delete cssStore[key];
        for (const key in valStore) delete valStore[key];

        jest.clearAllMocks();
    });

    // 測試 r() 是否能正確增加指定餐點數量
    test('r(num) should increase dish count by 1', () => {
        order.r(0);
        expect(order.getState().dish[0]).toBe(1);
    });

    // 測試 decr() 在數量大於 0 時是否能正確減少數量
    test('decr(num) should decrease dish count when value is greater than 0', () => {
        order.setState({
            dish: [2, 0, 0],
            originD: [2, 0, 0]
        });

        order.decr(0);
        expect(order.getState().dish[0]).toBe(1);
    });

    // 測試 decr() 是否能防止數量小於 0（Boundary tests）
    test('decr(num) should not make dish count below 0', () => {
        order.setState({
            dish: [0, 0, 0],
            originD: [0, 0, 0]
        });

        order.decr(0);
        expect(order.getState().dish[0]).toBe(0);
    });

    // 測試 clear() 是否能將所有餐點數量重置為 0
    test('clear() should reset all dish counts to 0', () => {
        order.setState({
            dish: [1, 2, 3],
            originD: [1, 2, 3]
        });

        order.clear();

        expect(order.getState().dish).toEqual([0, 0, 0]);
        expect(order.getState().originD).toEqual([0, 0, 0]);
    });

    // 測試 refresh() 是否能正確計算總金額
    test('refresh() should calculate total correctly', () => {
        order.setState({
            dish: [1, 2, 1],
            originD: [1, 2, 1]
        });

        order.refresh();

        expect(order.getState().total).toBe(270);
    });

    // 測試 refresh() 在沒有選擇餐點時是否清空畫面顯示
    test('refresh() should clear display when no dish is selected', () => {
        order.setState({
            dish: [0, 0, 0],
            originD: [0, 0, 0]
        });

        order.refresh();

        expect(htmlStore['#sendBtn']).toBe(' ');
        expect(htmlStore['#totalW']).toBe(' ');
        expect(htmlStore['#total']).toBe(' ');
    });

    // 測試 refresh() 在餐點數量變更時是否顯示高亮樣式
    test('refresh() should render highlighted row when originD and dish are different', () => {
        order.setState({
            dish: [2, 0, 0],
            originD: [1, 0, 0],
            total: 0
        });

        order.refresh();

        expect(htmlStore['#list']).toContain('background-color: #F0B27A');
        expect(order.getState().total).toBe(200);
    });

    // 測試 sendConfirm() 是否建立確認清單、更新總價並顯示確認視窗與播放提示音
    test('sendConfirm() should build confirm list, update total, show confirm box and play sound', () => {
        const playMock = jest.fn();
        document.getElementById.mockReturnValue({ play: playMock });

        order.setState({
            dish: [1, 2, 0],
            originD: [1, 2, 0],
            total: 0
        });

        order.sendConfirm();

        expect(htmlStore['#confirmList']).toContain('牛肉麵');
        expect(htmlStore['#confirmList']).toContain('炒飯');
        expect(htmlStore['#total2']).toBe(240);
        expect(cssStore['#confirmB']).toEqual({ display: 'block' });
        expect(cssStore['#content']).toEqual({
            filter: 'blur(10px)',
            '-webkit-filter': 'blur(10px)',
            'pointer-events': 'none'
        });
        expect(htmlStore['#notify']).toContain('audio');
        expect(document.getElementById).toHaveBeenCalledWith('sound');
        expect(playMock).toHaveBeenCalled();
    });

    // 測試 back() 是否關閉確認視窗並恢復原本畫面狀態
    test('back() should hide confirm box and restore content style', () => {
        order.back();

        expect(cssStore['#confirmB']).toEqual({ display: 'none' });
        expect(cssStore['#content']).toEqual({
            filter: 'blur(0px)',
            '-webkit-filter': 'blur(0px)',
            'pointer-events': 'initial'
        });
    });

    // 測試 send() 是否正確發送 AJAX 並在成功時更新畫面與狀態
    test('send() should send ajax request with correct data and handle success', () => {
        valStore['#num'] = '5';
        valStore['#type'] = '內用';

        order.setState({
            dish: [1, 2, 0],
            originD: [1, 2, 0],
            total: 240
        });

        order.send();

        expect($.ajax).toHaveBeenCalledTimes(1);

        const ajaxArg = $.ajax.mock.calls[0][0];
        expect(ajaxArg.url).toBe('php/send.php');
        expect(ajaxArg.type).toBe('POST');
        expect(ajaxArg.data).toEqual({
            num: '5',
            type: '內用',
            list: '1,2,0',
            total: 240
        });

        ajaxArg.success('ok');

        expect(orderPage).toHaveBeenCalled();
        expect(cssStore['#confirmB']).toEqual({ display: 'none' });
        expect(cssStore['#content']).toEqual({
            filter: 'blur(0px)',
            '-webkit-filter': 'blur(0px)',
            'pointer-events': 'initial'
        });
        expect(htmlStore['#confirmList']).toBe(' ');
    });

    // 測試 send() 在 AJAX 失敗時是否正確顯示錯誤提示
    test('send() should alert when ajax fails', () => {
        valStore['#num'] = '5';
        valStore['#type'] = '外帶';

        order.setState({
            dish: [1, 0, 0],
            originD: [1, 0, 0],
            total: 100
        });

        order.send();

        const ajaxArg = $.ajax.mock.calls[0][0];
        ajaxArg.error();

        expect(alert).toHaveBeenCalledWith('未成功送出訂單，請重試。');
    });
});