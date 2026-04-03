const path = require('path');

function loadMainModule() {
    jest.resetModules();

    const htmlStore = {};
    const cssStore = {};
    const valStore = {};
    const lengthStore = {};
    const elementStore = {};

    global.items = ['牛肉麵', '炒飯', '湯'];
    global.itemCount = 3;
    global.typeCount = 2;
    global.eachTypeCount = [2, 1];
    global.tableCount = 10;
    global.soundFileName1 = 'notify1.mp3';
    global.soundFileName2 = 'notify2.mp3';

    global.alert = jest.fn();
    global.confirm = jest.fn();
    global.clear = jest.fn();
    global.clearInterval = jest.fn();

    global.window = {
        setInterval: jest.fn()
    };

    global.document = {
        getElementById: jest.fn((id) => {
            if (!elementStore[id]) {
                elementStore[id] = {
                    play: jest.fn(),
                    setAttribute: jest.fn()
                };
            }
            return elementStore[id];
        })
    };

    global.$ = jest.fn((selector) => ({
        html: jest.fn((value) => {
            if (value !== undefined) htmlStore[selector] = value;
            return htmlStore[selector];
        }),
        css: jest.fn((value1, value2) => {
            if (value2 !== undefined) {
                if (!cssStore[selector]) cssStore[selector] = {};
                cssStore[selector][value1] = value2;
            } else if (value1 !== undefined) {
                cssStore[selector] = value1;
            }
            return cssStore[selector];
        }),
        val: jest.fn(() => valStore[selector] || ''),
        length: lengthStore[selector] || 0
    }));

    global.$.ajax = jest.fn();

    const main = require(path.join(__dirname, '../js/main.js'));

    return {
        main,
        htmlStore,
        cssStore,
        valStore,
        lengthStore,
        elementStore
    };
}

afterEach(() => {
    delete global.items;
    delete global.itemCount;
    delete global.typeCount;
    delete global.eachTypeCount;
    delete global.tableCount;
    delete global.soundFileName1;
    delete global.soundFileName2;
    delete global.alert;
    delete global.confirm;
    delete global.clear;
    delete global.clearInterval;
    delete global.window;
    delete global.document;
    delete global.$;
});

describe('main.js with full coverage', () => {
    test('orderPage() should load order page successfully', () => {
        const { main, htmlStore } = loadMainModule();

        main.orderPage();

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        expect(ajaxArg).toEqual(
            expect.objectContaining({
                url: 'php/orderPage.php',
                type: 'POST',
                datatype: 'html',
                success: expect.any(Function),
                error: expect.any(Function)
            })
        );

        ajaxArg.success('<div>order page</div>');

        expect(htmlStore['#titleText']).toBe('點餐管理系統 / 點餐頁面');
        expect(htmlStore['#content']).toBe('<div>order page</div>');
    });

    test('orderPage() should alert when ajax fails', () => {
        const { main } = loadMainModule();

        main.orderPage();

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.error();

        expect(global.alert).toHaveBeenCalledWith('Request failed.');
    });

    test('dineIn() should request dine-in page and clear dishes', () => {
        const { main, htmlStore } = loadMainModule();

        main.dineIn();

        expect(htmlStore['#menu']).toBe(' ');
        expect(global.clear).toHaveBeenCalled();

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        expect(ajaxArg).toEqual(
            expect.objectContaining({
                url: 'php/dineIn.php',
                type: 'POST',
                datatype: 'html',
                data: {
                    tableCount: global.tableCount
                },
                success: expect.any(Function),
                error: expect.any(Function)
            })
        );

        ajaxArg.success('<div>table list</div>');

        expect(htmlStore['#number']).toBe('<div>table list</div>');
        expect(htmlStore['#menu']).toBe('<h1>提示：請於右方選擇桌號</h1>');
    });

    test('dineIn() should alert when ajax fails', () => {
        const { main } = loadMainModule();

        main.dineIn();

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.error();

        expect(global.alert).toHaveBeenCalledWith('Request failed.');
    });

    test('toGo() should request to-go page and then show menu', () => {
        const { main, htmlStore } = loadMainModule();

        main.toGo();

        expect(htmlStore['#menu']).toBe(' ');
        expect(global.$.ajax).toHaveBeenCalledTimes(2);

        const firstAjax = global.$.ajax.mock.calls[0][0];
        expect(firstAjax).toEqual(
            expect.objectContaining({
                url: 'php/toGo.php',
                type: 'POST',
                datatype: 'html',
                success: expect.any(Function),
                error: expect.any(Function)
            })
        );

        firstAjax.success('<div>to go</div>');
        expect(htmlStore['#number']).toBe('<div>to go</div>');

        const secondAjax = global.$.ajax.mock.calls[1][0];
        expect(secondAjax).toEqual(
            expect.objectContaining({
                url: 'php/showMenu.php',
                type: 'POST',
                datatype: 'html',
                data: {
                    items: global.items,
                    itemCount: global.itemCount,
                    typeCount: global.typeCount,
                    eachTypeCount: global.eachTypeCount
                },
                success: expect.any(Function),
                error: expect.any(Function)
            })
        );
        expect(global.clear).toHaveBeenCalled();
    });

    test('toGo() should alert when first ajax fails', () => {
        const { main } = loadMainModule();

        main.toGo();

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.error();

        expect(global.alert).toHaveBeenCalledWith('Request failed.');
    });

    test('showMenu() should render menu successfully', () => {
        const { main, htmlStore } = loadMainModule();

        main.showMenu();

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        expect(ajaxArg).toEqual(
            expect.objectContaining({
                url: 'php/showMenu.php',
                type: 'POST',
                datatype: 'html',
                data: {
                    items: global.items,
                    itemCount: global.itemCount,
                    typeCount: global.typeCount,
                    eachTypeCount: global.eachTypeCount
                },
                success: expect.any(Function),
                error: expect.any(Function)
            })
        );

        ajaxArg.success('<div>menu</div>');

        expect(htmlStore['#menu']).toBe('<div>menu</div>');
        expect(global.clear).toHaveBeenCalled();
    });

    test('showMenu() should alert when ajax fails', () => {
        const { main } = loadMainModule();

        main.showMenu();

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.error();

        expect(global.alert).toHaveBeenCalledWith('Request failed.');
    });

    test('takePage() should update count without notify when Count1 is null', () => {
        const { main, htmlStore, lengthStore } = loadMainModule();
        lengthStore['.listB'] = 2;

        main.takePage();

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.success('<div>take page</div>');

        expect(htmlStore['#content']).toBe('<div>take page</div>');
        expect(main.getState().Count2).toBe(2);
        expect(main.getState().Count1).toBe(2);
        expect(htmlStore['#notify']).toBeUndefined();
        expect(global.document.getElementById).not.toHaveBeenCalledWith('sound');
    });

    test('takePage() should update count and play notify sound when list count increases', () => {
        const { main, htmlStore, lengthStore, elementStore } = loadMainModule();
        main.setState({ Count1: 1 });
        lengthStore['.listB'] = 3;

        main.takePage();

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.success('<div>take page</div>');

        expect(htmlStore['#content']).toBe('<div>take page</div>');
        expect(main.getState().Count2).toBe(3);
        expect(htmlStore['#notify']).toBe(main.getState().notify1);
        expect(global.document.getElementById).toHaveBeenCalledWith('sound');
        expect(elementStore['sound'].play).toHaveBeenCalled();
        expect(main.getState().Count1).toBe(3);
    });

    test('takePage() should alert and clear interval on ajax error', () => {
        const { main } = loadMainModule();
        main.setState({ interval01: 888 });

        main.takePage();

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.error();

        expect(global.alert).toHaveBeenCalledWith('Request failed.');
        expect(global.clearInterval).toHaveBeenCalledWith(888);
    });

    test('deliverPage() should update count without notify when count does not increase', () => {
        const { main, htmlStore, lengthStore } = loadMainModule();
        main.setState({ Count1: 3 });
        lengthStore['.listB'] = 3;

        main.deliverPage();

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.success('<div>deliver page</div>');

        expect(htmlStore['#content']).toBe('<div>deliver page</div>');
        expect(main.getState().Count2).toBe(3);
        expect(main.getState().Count1).toBe(3);
        expect(htmlStore['#notify']).toBeUndefined();
    });

    test('deliverPage() should play notify sound when list count increases', () => {
        const { main, htmlStore, lengthStore, elementStore } = loadMainModule();
        main.setState({ Count1: 1 });
        lengthStore['.listB'] = 2;

        main.deliverPage();

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.success('<div>deliver page</div>');

        expect(htmlStore['#content']).toBe('<div>deliver page</div>');
        expect(htmlStore['#notify']).toBe(main.getState().notify2);
        expect(global.document.getElementById).toHaveBeenCalledWith('sound');
        expect(elementStore['sound'].play).toHaveBeenCalled();
        expect(main.getState().Count1).toBe(2);
    });

    test('deliverPage() should clear interval on ajax error', () => {
        const { main } = loadMainModule();
        main.setState({ interval02: 999 });

        main.deliverPage();

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.error();

        expect(global.alert).toHaveBeenCalledWith('Request failed.');
        expect(global.clearInterval).toHaveBeenCalledWith(999);
    });

    test('autoRefresh01() should set title and interval', () => {
        const { main, htmlStore } = loadMainModule();
        global.window.setInterval.mockReturnValue(123);

        main.autoRefresh01();

        expect(global.$.ajax).toHaveBeenCalledWith(
            expect.objectContaining({
                url: 'php/takePage.php',
                type: 'POST',
                datatype: 'html',
                data: {
                    items: global.items,
                    itemCount: global.itemCount
                },
                success: expect.any(Function),
                error: expect.any(Function)
            })
        );
        expect(htmlStore['#titleText']).toBe('點餐管理系統 / 接單頁面');
        expect(global.window.setInterval).toHaveBeenCalledWith('takePage()', 1000);
        expect(main.getState().interval01).toBe(123);
    });

    test('autoRefresh02() should set title and interval', () => {
        const { main, htmlStore } = loadMainModule();
        global.window.setInterval.mockReturnValue(456);

        main.autoRefresh02();

        expect(global.$.ajax).toHaveBeenCalledWith(
            expect.objectContaining({
                url: 'php/deliverPage.php',
                type: 'POST',
                datatype: 'html',
                data: {
                    items: global.items,
                    itemCount: global.itemCount
                },
                success: expect.any(Function),
                error: expect.any(Function)
            })
        );
        expect(htmlStore['#titleText']).toBe('點餐管理系統 / 送餐頁面');
        expect(global.window.setInterval).toHaveBeenCalledWith('deliverPage()', 1000);
        expect(main.getState().interval02).toBe(456);
    });

    test('complete() should send complete request and then reload take page', () => {
        const { main } = loadMainModule();

        main.complete(9);

        expect(global.$.ajax).toHaveBeenCalledTimes(1);
        const ajaxArg = global.$.ajax.mock.calls[0][0];
        expect(ajaxArg).toEqual(
            expect.objectContaining({
                url: 'php/complete.php',
                type: 'POST',
                datatype: 'html',
                data: {
                    Auto: 9
                },
                success: expect.any(Function),
                error: expect.any(Function)
            })
        );

        ajaxArg.success('ok');

        expect(global.$.ajax).toHaveBeenCalledTimes(2);
        expect(global.$.ajax.mock.calls[1][0]).toEqual(
            expect.objectContaining({
                url: 'php/takePage.php'
            })
        );
    });

    test('complete() should alert when ajax fails', () => {
        const { main } = loadMainModule();

        main.complete(9);

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.error();

        expect(global.alert).toHaveBeenCalledWith('Request failed.');
    });

    test('done() should send done request and then reload deliver page', () => {
        const { main } = loadMainModule();

        main.done(7);

        expect(global.$.ajax).toHaveBeenCalledTimes(1);
        const ajaxArg = global.$.ajax.mock.calls[0][0];
        expect(ajaxArg).toEqual(
            expect.objectContaining({
                url: 'php/done.php',
                type: 'POST',
                datatype: 'html',
                data: {
                    Auto: 7
                },
                success: expect.any(Function),
                error: expect.any(Function)
            })
        );

        ajaxArg.success('ok');

        expect(global.$.ajax).toHaveBeenCalledTimes(2);
        expect(global.$.ajax.mock.calls[1][0]).toEqual(
            expect.objectContaining({
                url: 'php/deliverPage.php'
            })
        );
    });

    test('done() should alert when ajax fails', () => {
        const { main } = loadMainModule();

        main.done(7);

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.error();

        expect(global.alert).toHaveBeenCalledWith('Request failed.');
    });

    test('historyPage() should load history page successfully', () => {
        const { main, htmlStore } = loadMainModule();

        main.historyPage();

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        expect(ajaxArg).toEqual(
            expect.objectContaining({
                url: 'php/historyPage.php',
                type: 'POST',
                datatype: 'html',
                success: expect.any(Function),
                error: expect.any(Function)
            })
        );

        ajaxArg.success('<div>history page</div>');

        expect(htmlStore['#titleText']).toBe('點餐管理系統 / 歷史訂單');
        expect(htmlStore['#content']).toBe('<div>history page</div>');
    });

    test('historyPage() should alert when ajax fails', () => {
        const { main } = loadMainModule();

        main.historyPage();

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.error();

        expect(global.alert).toHaveBeenCalledWith('Request failed.');
    });

    test('history(type, page) should request correct ajax data', () => {
        const { main } = loadMainModule();

        main.history('dinein', 3);

        expect(global.$.ajax).toHaveBeenCalledWith(
            expect.objectContaining({
                url: 'php/history.php',
                type: 'POST',
                datatype: 'html',
                data: {
                    type: 'dinein',
                    page: 3
                },
                success: expect.any(Function),
                error: expect.any(Function)
            })
        );
    });

    test('history() should render history content on success', () => {
        const { main, htmlStore } = loadMainModule();

        main.history('takeout', 2);

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.success('<div>history rows</div>');

        expect(htmlStore['#historyA']).toBe('<div>history rows</div>');
    });

    test('history() should alert when ajax fails', () => {
        const { main } = loadMainModule();

        main.history('takeout', 2);

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.error();

        expect(global.alert).toHaveBeenCalledWith('Request failed.');
    });

    test('view(auto, type) should show detail popup and bind remove button', () => {
        const { main, htmlStore, cssStore, elementStore } = loadMainModule();

        main.view(8, 2);

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        expect(ajaxArg.url).toBe('php/detail.php');
        expect(ajaxArg.data).toEqual({
            auto: 8,
            items: global.items,
            itemCount: global.itemCount
        });

        ajaxArg.success('<tr><td>detail</td></tr>');

        expect(htmlStore['#detailedList']).toBe('<tr><td>detail</td></tr>');
        expect(cssStore['#detailB']).toEqual({ display: 'block' });
        expect(cssStore['#content']).toEqual({
            filter: 'blur(10px)',
            '-webkit-filter': 'blur(10px)',
            'pointer-events': 'none'
        });
        expect(elementStore['remove'].setAttribute).toHaveBeenCalledWith(
            'onclick',
            'remove(8,2)'
        );
    });

    test('view() should alert when ajax fails', () => {
        const { main } = loadMainModule();

        main.view(8, 2);

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.error();

        expect(global.alert).toHaveBeenCalledWith('Request failed.');
    });

    test('back02() should hide detail dialog and restore content style', () => {
        const { main, cssStore } = loadMainModule();

        main.back02();

        expect(cssStore['#detailB']).toEqual({ display: 'none' });
        expect(cssStore['#content']).toEqual({
            filter: 'blur(0px)',
            '-webkit-filter': 'blur(0px)',
            'pointer-events': 'initial'
        });
    });

    test('remove() should not send ajax when confirm returns false', () => {
        const { main } = loadMainModule();
        global.confirm.mockReturnValue(0);

        main.remove(5, 1);

        expect(global.$.ajax).not.toHaveBeenCalled();
    });

    test('remove() should call ajax when confirm returns true', () => {
        const { main, cssStore } = loadMainModule();
        global.confirm.mockReturnValue(1);

        main.remove(5, 1);

        expect(global.$.ajax).toHaveBeenCalledTimes(1);
        expect(global.$.ajax).toHaveBeenCalledWith(
            expect.objectContaining({
                url: 'php/remove.php',
                type: 'POST',
                datatype: 'html',
                data: {
                    auto: 5
                },
                success: expect.any(Function),
                error: expect.any(Function)
            })
        );

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.success('ok');

        expect(cssStore['#detailB']).toEqual({ display: 'none' });
        expect(cssStore['#content']).toEqual({
            filter: 'blur(0px)',
            '-webkit-filter': 'blur(0px)',
            'pointer-events': 'initial'
        });

        expect(global.$.ajax).toHaveBeenCalledTimes(3);
        expect(global.$.ajax.mock.calls[1][0]).toEqual(
            expect.objectContaining({
                url: 'php/historyPage.php',
                type: 'POST',
                datatype: 'html'
            })
        );
        expect(global.$.ajax.mock.calls[2][0]).toEqual(
            expect.objectContaining({
                url: 'php/history.php',
                type: 'POST',
                datatype: 'html',
                data: {
                    type: 1,
                    page: 1
                }
            })
        );
    });

    test('remove() should alert when delete ajax fails', () => {
        const { main } = loadMainModule();
        global.confirm.mockReturnValue(1);

        main.remove(5, 1);

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.error();

        expect(global.alert).toHaveBeenCalledWith('Error:暫時無法刪除');
    });

    test('removeAll() should do nothing when confirm returns false', () => {
        const { main } = loadMainModule();
        global.confirm.mockReturnValue(0);

        main.removeAll();

        expect(global.$.ajax).not.toHaveBeenCalled();
    });

    test('removeAll() should call ajax when confirm returns true', () => {
        const { main } = loadMainModule();
        global.confirm.mockReturnValue(1);

        main.removeAll();

        expect(global.$.ajax).toHaveBeenCalledTimes(1);
        expect(global.$.ajax).toHaveBeenCalledWith(
            expect.objectContaining({
                url: 'php/removeAll.php',
                type: 'POST',
                datatype: 'html',
                success: expect.any(Function),
                error: expect.any(Function)
            })
        );

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.success('ok');

        expect(global.$.ajax).toHaveBeenCalledTimes(2);
        expect(global.$.ajax.mock.calls[1][0]).toEqual(
            expect.objectContaining({
                url: 'php/historyPage.php',
                type: 'POST',
                datatype: 'html'
            })
        );
    });

    test('removeAll() should alert when delete-all ajax fails', () => {
        const { main } = loadMainModule();
        global.confirm.mockReturnValue(1);

        main.removeAll();

        const ajaxArg = global.$.ajax.mock.calls[0][0];
        ajaxArg.error();

        expect(global.alert).toHaveBeenCalledWith('Error:暫時無法刪除');
    });
});