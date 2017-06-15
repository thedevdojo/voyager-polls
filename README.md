<p align="center"><a href="https://the-control-group.github.io/voyager/" target="_blank"><img width="220" src="/readme-logo.png?raw=true"></a></p>

# Polls
This is a hook for Voyager that allows you to add interactive polls to your application.

## Installation

You will need to have [Laravel Voyager](https://github.com/the-control-group/voyager) installed along with [Voyager Hooks](https://github.com/larapack/voyager-hooks). Then you can run the following artisan command:

```bash
php artisan hook:install voyager-polls
```

You should then get a message back that says something similar to: *Hook [voyager-polls] have been installed.*

Next, visit your admin and click on the Hooks menu Item and you will be at the Voyager Hooks page where you can click to enable the `voyager-polls` hook.

![Voyager Hooks Screenshot](http://i.imgur.com/O6iHYeF.png)

After clicking on enable, you will see a new menu item has been added:

![Voyager Polls Menu Item](http://i.imgur.com/lPfsnMb.png)

If you do not see this menu item try reloading the page again and you should see it in the menu. You may also wish to change the order of the menu item inside of the Menu Builder.

## Usage

On the Admin Polls page you can click on the 'Add New' button and you will be at the poll creation page:

![Create new poll](http://i.imgur.com/2Gd3jK6.png)

You can create a new poll with as many questions and answers as you would like. As you create your new poll, you will see a live preview of what this can look like in the preview.

Add as many polls as you would like, and then move on to learn how you can integrate the polls on the front-end of your site.

## Implement Your Poll on the front-end

Voyager Polls allows you to easily embed the poll custom component using Vue.js. inside of your main `app.js` file of your app you'll want to include the **poll** component:

```
Vue.component('poll', require('../../../hooks/voyager-polls/resources/assets/js/poll.vue'));

const app = new Vue({
    el: '#app'
});
```

Make sure to compile your app.js and now you can use the poll like the following in your application:

```
<div id="app">
    <poll slug="slug-name"></poll>
</div>
```

And your new functional poll will be visible and ready for your users.

![Poll preview](http://i.imgur.com/IcfCVlt.png)

> Remember with any VueJS component you can override the default styles by adding custom styles in your application.

## Embedding a Poll in a Rich Text Box

You may also want to embed a poll somewhere in a Rich Text Box. You can easily do that by adding a custom javascript file to your Voyager config. Inside of your voyager configuation there is an option to add some additional javascript:

```
'additional_js' => [
    'js/voyager_custom.js',
],
```

Then within your `voyager_custom.js` file you will need to add the following to add the poll plugin to your Rich Text Editor:

```

require ('../../../hooks/voyager-polls/resources/assets/js/tinymce-polls-plugin');


tinymce.init({
    menubar: false,
    selector:'textarea.richTextBox',
    skin: 'voyager',
    plugins: 'link, image, code, youtube, giphy, pagebreak, poll',
    extended_valid_elements : 'input[onclick|value|style|type],poll[slug]',
    custom_elements: 'poll',
    file_browser_callback: function(field_name, url, type, win) {
        if(type =='image'){
          $('#upload_file').trigger('click');
        }
    },
    toolbar: 'styleselect bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link image youtube giphy pastetext pasteword | code pagebreak poll',
    convert_urls: false,
    image_caption: true,
    image_title: true,
    poll_tag: '<poll slug=""></poll>',
});
```

## Further Development - How to compile the assets

Make sure that you have webpack installed globally

`cd` into the  `voyager-polls` directory, and install the **node dependencies**:

```
npm install
```

Now we can compile assets with the following commands

```
npm run production
```

or to have it watch for files, run:

```
npm run watch
```

