var editor_options={

holder: 'editorjs',
onReady: function(){

},
onChange: function() {
console.log('something changed');
},
placeholder:'<?php echo __("backend/editjs.placeholder"); ?>',

tools: {
/**
* Each Tool is a Plugin. Pass them via 'class' option with necessary settings {@link docs/tools.md}
*/
header: {
class: Header,
inlineToolbar: ['marker', 'link'],
config: {
placeholder: 'Header'
},
shortcut: 'CMD+SHIFT+H'
},

/**
* Or pass class directly without any configuration
*/
image: SimpleImage,

list: {
class: List,
inlineToolbar: true,
shortcut: 'CMD+SHIFT+L'
},

checklist: {
class: Checklist,
inlineToolbar: true,
},

quote: {
class: Quote,
inlineToolbar: true,
config: {
quotePlaceholder: 'Enter a quote',
captionPlaceholder: 'Quote\'s author',
},
shortcut: 'CMD+SHIFT+O'
},

warning: Warning,

marker: {
class:  Marker,
shortcut: 'CMD+SHIFT+M'
},

code: {
class:  CodeTool,
shortcut: 'CMD+SHIFT+C'
},

delimiter: Delimiter,

inlineCode: {
class: InlineCode,
shortcut: 'CMD+SHIFT+C'
},

linkTool: LinkTool,

embed: Embed,

table: {
class: Table,
inlineToolbar: true,
shortcut: 'CMD+ALT+T'
},

},


}
