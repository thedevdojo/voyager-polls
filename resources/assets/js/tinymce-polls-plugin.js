//tinymce.activeEditor.dom.addStyle('poll {width: 100%; height: 70px; background: #f1f1f1; display: block;}');

tinymce.PluginManager.add('poll', function(editor, url) {
  var component = editor.getParam("poll_tag", "<poll slug=\"\"></poll>"),
    editorClass = 'mce-poll',
    customStyles = "poll {width: 100%; height: 70px; background: #f1f1f1; display: block; border:1px solid #ddd; border-radius:2px;} poll:after{content:'Poll'; width:100%; height:70px; color:#999; font-size:16px; text-align:center; position:absolute; line-height:70px; }",
    replaceRegEx = /<poll.*><\/poll>/gi;
  
  if(typeof(editor.settings.content_style) != "undefined"){
    editor.settings.content_style += customStyles;
  } else {
    editor.settings.content_style = customStyles;
  }

  console.log();
  //editor.addStyle('poll {width: 100%; height: 70px; background: #f1f1f1; display: block;}');

  // Add command - similar to events for mce
  editor.addCommand("InsertPoll", function(){
    editor.windowManager.open({
      title: 'Poll',
      body: [
        {type: 'textbox', name: 'slug', label: 'Slug', value:component.match(/slug\=\"([\w- !?]*)/)[1]}
      ],
      onsubmit: function(e) {
        if(component.match(/slug\=\"([\w- !?]*)/)[1] == ""){
          component = component.replace('slug=""', 'slug="' + e.data.slug + '"');
        } else {
          component = component.replace(component.match(/slug\=\"([\w- !?]*)/)[1],e.data.slug);
        }
        
        //console.log(component);
        // Insert content when the window form is submitted
        editor.execCommand("mceInsertContent", true, component);
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
  

  // editor.on("ResolveName", function(event) {
  //   if("IMG" === event.target.nodeName && editor.dom.hasClass(event.target, editorClass)){
  //     event.name = "Poll";
  //   }
  // });


  // editor.on("click", function(event) {
  //   if("IMG" === event.target.nodeName && editor.dom.hasClass(event.target, editorClass)){
  //     editor.selection.select(event.target);
  //   }
  // });


  // editor.on("BeforeSetContent", function(contentEvent) {
  //   //hack way of setting on init - but if there are multiple they still all get the same value.
  //   var findCurrent = contentEvent.content.match(replaceRegEx);
  //   component = findCurrent && findCurrent.length > 0 ? findCurrent[0] : component;
  //   contentEvent.content = contentEvent.content.replace(replaceRegEx, editorPlaceholder);
  // });


  // editor.on("PreInit", function() {
  //   editor.serializer.addNodeFilter("img", function(t) {
  //     for (var r, i, o = t.length; o--;) {
  //       if (r = t[o], i = r.attr("class"), i && -1 !== i.indexOf(editorClass)) {
  //           var a = r.parent;
  //           if (editor.schema.getBlockElements()[a.name]) {
  //               a.type = 3;
  //               a.value = component;
  //               a.raw = !0;
  //               r.remove();
  //               continue;
  //           }
  //           r.type = 3;
  //           r.value = component;
  //           r.raw = !0;
  //       }
  //     }
  //   });
  // });


});