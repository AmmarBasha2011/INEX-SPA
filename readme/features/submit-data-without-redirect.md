# Submit Data without redirect

You can submit data without redirect not refresh and get the request response by `submitDataWR` this is Javascript function likely to `submitData` function, use in `<form>` for CSRF Token, and add to `<button type="button">` .

You can call it by:

```javascript
submitDataWR('routeName', 'inputIds'=[], requestType='POST', customValues=[])
```

like:

```javascript
submitDataWR('setLanguage', [], 'POST', ['lang':'en'])
```
