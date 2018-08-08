jQuery-SimpleLens
=================

This is a simple script to magnify images.
The files needed to make it work are these:
- src/jquery.simpleGallery.js manages a simple gallery with thumbnails and big images.
- src/jquery.simpleLens.js does all the work for the magnifying of the images.
- css/jquery.simpleLens.css provides the css code needed from the magnifying script.

**jquery.simpleGallery.js is optional and you can use any plugin that does the same thing.**

The demo folder contains all the file needed for the demo.
In the demo.html file you can find some examples.

**The basic html markup to make the lens plugin work is this**:
```html
<div class="gallery-container" id="demo-1">
    <div class="container">
        <div class="big-image-container">
            <a class="lens-image" data-lens-image="demo/large/image.jpg">
                <img src="demo/medium/image.jpg" class="big-image">
            </a>
        </div>
    </div>
</div>
```

**And this is the javascript initialization code**:
```javascript
$('#demo-1 .big-image').simpleLens({
    loading_image: 'demo/images/loading.gif'
});
```

**Options**:
```javascript
$.fn.simpleLens.defaults = {
    anchor_parent_class: '.lens-image',
    lens_image_attr: 'data-lens-image',
    big_image_class: '.big-image',
    parent_class: '.big-image-container',
    lens_class: 'lens-element',
    cursor_class: 'mouse-cursor',
    loading_image: 'images/loading.gif',
    open_lens_event: 'mouseenter'
};
```