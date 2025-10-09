function serverSideButtonAction(e, dt, node, config) {

    var me = this;
    var button = config.text.toLowerCase();
    if (typeof $.fn.dataTable.ext.buttons[button] === "function") {
      button = $.fn.dataTable.ext.buttons[button]();
    }
    var len = dt.page.len();
    var start = dt.page();
    dt.page(0);

    // Assim que ela acabar de desenhar todas as linhas eu executo a função do botão.
    // ssb de serversidebutton
    dt.context[0].aoDrawCallback.push({
      "sName": "ssb",
      "fn": function () {
        $.fn.dataTable.ext.buttons[button].action.call(me, e, dt, node, config);
        dt.context[0].aoDrawCallback = dt.context[0].aoDrawCallback.filter(function (e) { return e.sName !== "ssb" });
      }
    });
    dt.page.len(999999999).draw();
    setTimeout(function () {
      dt.page(start);
      dt.page.len(len).draw();
    }, 5000);
  }