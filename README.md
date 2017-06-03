# Polls
This is a hook for Voyager that allows you to add interactive polls to your application.

```bash
php artisan hook:install polls
```

## How to compile the assets

Make sure that you have webpack installed globally

```
npm install -g webpack
```

Then `cd` into the  `voyager-polls` directory, and install the **node dependencies**:

```
npm install
```

Now we can run webpack with the following commands

```
webpack
```

or to have it watch for files, run:

```
webpack --watch
```

