const htmlStore = {};
const valStore = {};

function createFakeSelection() {
    const selection = {
        attr: jest.fn((name, value) => {
            if (value === undefined) {
                if (name === 'width') return 900;
                if (name === 'height') return 800;
                return selection;
            }

            // 如果 attr 的值是 function，主動執行一次
            if (typeof value === 'function') {
                value({ year: '1月', value: '100' });
            }

            return selection;
        }),
        append: jest.fn(() => createFakeSelection()),
        text: jest.fn(() => selection),
        call: jest.fn(() => selection),
        data: jest.fn(() => selection),
        enter: jest.fn(() => selection),
        selectAll: jest.fn(() => selection)
    };
    return selection;
}

function createScaleBandMock() {
    const scale = jest.fn((value) => value);
    scale.range = jest.fn(() => scale);
    scale.padding = jest.fn(() => scale);
    scale.domain = jest.fn(() => scale);
    scale.bandwidth = jest.fn(() => 50);
    return scale;
}

function createScaleLinearMock() {
    const scale = jest.fn((value) => Number(value));
    scale.range = jest.fn(() => scale);
    scale.domain = jest.fn(() => scale);
    return scale;
}

global.alert = jest.fn();

global.$ = jest.fn((selector) => ({
    html: jest.fn((value) => {
        if (value !== undefined) htmlStore[selector] = value;
        return htmlStore[selector];
    }),
    val: jest.fn(() => valStore[selector] ?? null)
}));

global.$.ajax = jest.fn();

global.d3 = {
    select: jest.fn(() => createFakeSelection()),
    scaleBand: jest.fn(() => createScaleBandMock()),
    scaleLinear: jest.fn(() => createScaleLinearMock()),
    csvParse: jest.fn(() => [
        { year: '1月', value: '100' },
        { year: '2月', value: '200' }
    ]),
    max: jest.fn((data, fn) => Math.max(...data.map(fn))),
    axisBottom: jest.fn(() => jest.fn()),
    axisLeft: jest.fn(() => {
        const axis = jest.fn();
        axis.tickFormat = jest.fn((formatter) => {
            // 主動執行 tickFormat 的 callback
            formatter(100);
            return {
                ticks: jest.fn(() => axis)
            };
        });
        return axis;
    })
};

const { searchYear, draw } = require('../../js/D3');

describe('D3.js unit tests', () => {
    beforeEach(() => {
        for (const key in htmlStore) delete htmlStore[key];
        for (const key in valStore) delete valStore[key];
        jest.clearAllMocks();
    });

    // 測試 searchYear() 是否發送正確 AJAX 請求
    test('searchYear() should call ajax with correct config', () => {
        searchYear();

        expect($.ajax).toHaveBeenCalledTimes(1);
        const ajaxArg = $.ajax.mock.calls[0][0];

        expect(ajaxArg.url).toBe('php/searchYear.php');
        expect(ajaxArg.type).toBe('POST');
        expect(ajaxArg.datatype).toBe('html');
    });

    // 測試 searchYear() 成功後是否更新畫面內容
    test('searchYear() should update content on success', () => {
        searchYear();

        const ajaxArg = $.ajax.mock.calls[0][0];
        ajaxArg.success('<p>history</p>');

        expect(htmlStore['#content']).toContain('historyA');
        expect(htmlStore['#historyA']).toBe('<p>history</p>');
    });

    // 測試 draw() 在未選擇年份時是否顯示提示訊息
    test('draw() should show warning when year is null', () => {
        valStore['#num'] = null;

        draw();

        expect(htmlStore['#drawA']).toContain('請先選擇查詢年份');
        expect($.ajax).not.toHaveBeenCalled();
    });

    // 測試 draw() 在有年份時是否正確發送 AJAX 請求
    test('draw() should call ajax with selected year', () => {
        valStore['#num'] = '2024';

        draw();

        expect($.ajax).toHaveBeenCalledTimes(1);
        const ajaxArg = $.ajax.mock.calls[0][0];

        expect(ajaxArg.url).toBe('php/returnD3.php');
        expect(ajaxArg.type).toBe('POST');
        expect(ajaxArg.data.year).toBe('2024');
    });

    // 測試 draw() 成功後是否建立 SVG 並呼叫 d3 繪圖流程
    test('draw() should create svg and use d3 on success', () => {
        valStore['#num'] = '2024';

        draw();

        const ajaxArg = $.ajax.mock.calls[0][0];
        ajaxArg.success('year,value\n1月,100\n2月,200');

        expect(htmlStore['#drawA']).toContain('<svg');
        expect(d3.select).toHaveBeenCalledWith('svg');
        expect(d3.csvParse).toHaveBeenCalled();
        expect(d3.scaleBand).toHaveBeenCalled();
        expect(d3.scaleLinear).toHaveBeenCalled();
        expect(d3.axisBottom).toHaveBeenCalled();
        expect(d3.axisLeft).toHaveBeenCalled();
    });

    // 測試 draw() 成功後是否正確解析資料最大值
    test('draw() should compute max value from parsed csv data', () => {
        valStore['#num'] = '2024';

        draw();

        const ajaxArg = $.ajax.mock.calls[0][0];
        ajaxArg.success('year,value\n1月,100\n2月,200');

        expect(d3.max).toHaveBeenCalled();
    });

    // 測試 searchYear() 在 AJAX 失敗時是否顯示錯誤提示
    test('searchYear() should alert on ajax error', () => {
        searchYear();

        const ajaxArg = $.ajax.mock.calls[0][0];
        ajaxArg.error();

        expect(alert).toHaveBeenCalledWith('Request failed.');
    });

    // 測試 draw() 在 AJAX 失敗時是否顯示錯誤提示
    test('draw() should alert on ajax error', () => {
        valStore['#num'] = '2024';

        draw();

        const ajaxArg = $.ajax.mock.calls[0][0];
        ajaxArg.error();

        expect(alert).toHaveBeenCalledWith('Request failed.');
    });
});