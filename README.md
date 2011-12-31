PHP-Image
===
The PHP-Image library currently includes 9 classes (all instantiable) which help
in the generation of two types of images: **resized** and
**&quot;squared&quot;**.

The reason for the large number of files for a fundamentally straightforward
process was to decouple all the different implementation threads going on (eg.
file types, manipulation engines, manipulation types).

### Resizing
The resizing of an image, quite simply, scales an image down to a desired size.
It assumes that the value the image is being resized to (when calling an
**Image** instance&#039;s **resize** method, an integer must be passed) ought to
be the maximum dimension.

For example, if an image is is 440x580, and it is resized with a value of 200
passed, the higher dimension of the two (580) is what is used as a base, with
it&#039;s new calculated dimension being 151.7x200.

### Squaring
I refer to squaring as the act of taking an image (eg. 440x580) and creating a
square version of it of ideal dimensions. If I were to square the 440x550 image
referenced to a size of 100, it&#039;s dimensions would be 100x100, with
31.81 pixels cropped cumutatively (but equally) from the top and bottom of the
image.

The reason for this is that when this image is resized to a maximum dimension of
100 pixels for it&#039;s lowest value (eg. it#039;s width or height), it#039;s
resized dimension would be 100x131.81. In order to square it, the higher of the
two dimensions must have it&#039;s excess &quot;shaved&quot; off.

This allows for the minimum amount to be cropped in order to create a square
version of the image, of the desired dimensions.

### Squaring Example

    // library inclusion
    require_once APP . '/vendors/PHP-ImageHelper/Image.class.php';
    
    // instantiation with image path
    $image = (new Image(APP . '/webroot/kittens.jpg'));
    
    // header definiton; squaring of image output
    header('Content-Type: image/jpeg');
    echo $image->square(100);
    exit(0);
