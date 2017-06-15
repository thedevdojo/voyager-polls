Using Vue.js to send and recieve data through an ajax request is super simple. To do this you will need a third party library called **Axios**.

You will want to include **Axios** in your project by calling:

```bash
npm install axios
```

Then where ever you want to make your Ajax requests you will need to require the **Axios** library like so:

```javascript
var axios = require('axios');
```

Now we can easily use the `axios` function to submit a post request anywhere in our application:

```javascript
axios.post('/url', { data: this.data })
    .then(function (response) {
        console.log(response);
    })
    .catch(function (error) {
        // Wu oh! Something went wrong
        console.log(error.message);
    });
```

So, if you were going to submit data inside of a *Vue* method called `buttonClicked` it would look like this:

```javascript
methods: {
    buttonClicked: function(){
        axios.post('/url', { data: this.data })
            .then(function (response) {
                console.log(response);
            })
            .catch(function (error) {
                // Wu oh! Something went wrong
                console.log(error.message);
            });
    }
}
```

You can also perform GET requests just as easy!

```javascript
axios.get('/url')
    .then(function (response) {
        console.log(response);
    })
    .catch(function (error) {
        console.log(error.message);
    });
```

You can find out more about different requests and additionaly configurations of using **Axios** at their Github page: [https://github.com/mzabriskie/axios](https://github.com/mzabriskie/axios).

> Quick bonus and tip for submitting ajax data using laravel:

If you've ever used the `$request->ajax()` method in Laravel to detect if your application method is being called from an Ajax request, you may notice that when you make an ajax request using **Axios** the `ajax()` function is going to return false.

This is because you need to add an additional configuration to **Axios** to send the correct headers:

```javascript
created: function(){
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}
```

If you add that code to your Vue.js `created` function, you will now be able to detect `ajax()` requests in your Laravel app:

```php
public function submitData(Request $request){
    if($request->ajax()){
        echo 'Success, we can now detect when an ajax request!';
    } else {
        echo 'Nope, not an ajax request.';
    }
}
```

Now... A Quick GIF to end this tutorial.

*When you fix a bug right before putting into production*

![Funny Gif](https://devdojo.com/uploads/images/June2017/rnLA5Dl-1497483767.gif)