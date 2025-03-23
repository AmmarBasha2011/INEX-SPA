# 2- Manually Download

You can use Manually Download instead of `ammar` CLI, if library not found on Github or found any errors in `ammar` CLI:

* 1- Create a folder with library name in `/core/import`&#x20;
* 2- Add library code to this folder
* 3- open `/core/import/package.json`&#x20;
* 4- edit and add this line:

```json
{
    "dependencies": {
        `libraryName`: `libraryURL`
    }
}
```
