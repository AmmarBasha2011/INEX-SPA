# JavaScript Execution Conflicts

Avoid using JavaScript across consecutive page transitions:

```html
<!-- Issue: Scripts won't execute properly -->
Page 1: 
<button onclick='redirect("page2")'>Next</button>
<script>console.log('page1 script');</script>

Page 2:
<script>redirect('page1');</script>
```
