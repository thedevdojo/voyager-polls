tinymce.PluginManager.add('poll', function(editor, url) {
  var component = editor.getParam("poll_tag", "<poll slug=\"\"></poll>"),
    editorClass = 'mce-poll',
    editorPlaceholder = '<img style="border:solid 1px black;width:100%;height:60px;" src="' + tinymce.Env.transparentSrc + '" class="' + editorClass + '" alt="Poll" data-mce-resize="false" data-mce-placeholder />',
    replaceRegEx = /<poll.*><\/poll>/gi;
  
  // Add command - similar to events for mce
  editor.addCommand("InsertPoll", function(){
    editor.windowManager.open({
      title: 'Poll',
      body: [
        {type: 'textbox', name: 'title', label: 'Title', value:component.match(/slug\=\"([\w !?]*)/)[1]}
      ],
      onsubmit: function(e) {
        component = component.replace(component.match(/slug\=\"([\w !?]*)/)[1],e.data.title);
        // Insert content when the window form is submitted
        editor.execCommand("mceInsertContent", false, component);
      }
    });
  });

  // Add a button
  editor.addButton('poll', {
    text: 'Poll',
    icon: false,
    tooltip: "Embed a Poll",
    cmd: "InsertPoll",
  });
  // Adds a menu item to the tools menu
  editor.addMenuItem('poll', {
    text: 'Poll',
    icon: false,
    tooltip: "Embed a Poll",
    cmd: "InsertPoll",
    context: "insert",
  });
  // used the pagebreak plugin as an example.
  editor.on("ResolveName", function(event) {
    if("IMG" === event.target.nodeName && editor.dom.hasClass(event.target, editorClass)){
      event.name = "Poll";
    }
  });
  editor.on("click", function(event) {
    if("IMG" === event.target.nodeName && editor.dom.hasClass(event.target, editorClass)){
      editor.selection.select(event.target);
    }
  });
  editor.on("BeforeSetContent", function(contentEvent) {
    //hack way of setting on init - but if there are multiple they still all get the same value.
    var findCurrent = contentEvent.content.match(replaceRegEx);
    component = findCurrent && findCurrent.length > 0 ? findCurrent[0] : component;
    contentEvent.content = contentEvent.content.replace(replaceRegEx, editorPlaceholder);
  });
  editor.on("PreInit", function() {
    editor.serializer.addNodeFilter("img", function(t) {
      for (var r, i, o = t.length; o--;) {
        if (r = t[o], i = r.attr("class"), i && -1 !== i.indexOf(editorClass)) {
            var a = r.parent;
            if (editor.schema.getBlockElements()[a.name]) {
                a.type = 3;
                a.value = component;
                a.raw = !0;
                r.remove();
                continue;
            }
            r.type = 3;
            r.value = component;
            r.raw = !0;
        }
      }
    });
  });
});