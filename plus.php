<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Filepicker - Multi file uploader</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,600,500,700">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.0/cropper.min.css">
    <link rel="stylesheet" href="assets/css/demo.css">
    <link rel="stylesheet" href="assets/css/fileicons.css">
    <link rel="stylesheet" href="assets/css/filepicker.css">
</head>
<body>
    <?php require __DIR__.'/_navbar.php'; ?>

    <div class="container">
        <div class="demo-container col-md-9 col-md-offset-2">
            <?php demo_nav(); ?>

            <div id="filepicker">
                <!-- Button Bar -->
                <div class="button-bar">
                    <div class="btn btn-success fileinput">
                        <i class="fa fa-plus"></i> Add files
                        <input type="file" name="files[]" multiple>
                    </div>

                    <button type="button" class="btn btn-primary camera-show">
                        <i class="fa fa-camera"></i> Camera
                    </button>

                    <button type="button" class="btn btn-info start-all">
                        <i class="fa fa-arrow-circle-o-up"></i> Start all
                    </button>

                    <button type="button" class="btn btn-warning cancel-all">
                        <i class="fa fa-ban"></i> Cancel all
                    </button>

                    <button type="button" class="btn btn-danger delete-all">
                        <i class="fa fa-trash-o"></i> Delete all
                    </button>
                </div>

                <!-- Files -->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="column-preview">Preview</th>
                                <th class="column-name">Name</th>
                                <th class="column-size">Size</th>
                                <th class="column-date">Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="files"></tbody>
                        <tfoot><tr><td colspan="5">No files where found.</td></tr></tfoot>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-container text-center"></div>

                <!-- Drop Window -->
                <div class="drop-window">
                    <div class="drop-window-content">
                        <h3><i class="fa fa-upload"></i> Drop files to upload</h3>
                    </div>
                </div>
            </div><!-- end of #filepicker -->
        </div>
    </div>

    <!-- Crop Modal -->
    <div id="crop-modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close" data-dismiss="modal">&times;</span>
                    <h4 class="modal-title">Make a selection</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning crop-loading">Loading image...</div>
                    <div class="crop-preview"></div>
                </div>
                <div class="modal-footer">
                    <div class="crop-rotate">
                        <button type="button" class="btn btn-default btn-sm crop-rotate-left" title="Rotate left">
                            <i class="fa fa-undo"></i>
                        </button>
                        <button type="button" class="btn btn-default btn-sm crop-flip-horizontal" title="Flip horizontal">
                            <i class="fa fa-arrows-h"></i>
                        </button>
                        <!-- <button type="button" class="btn btn-default btn-sm crop-flip-vertical" title="Flip vertical">
                            <i class="fa fa-arrows-v"></i>
                        </button> -->
                        <button type="button" class="btn btn-default btn-sm crop-rotate-right" title="Rotate right">
                            <i class="fa fa-repeat"></i>
                        </button>
                    </div>
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success crop-save" data-loading-text="Saving...">Save</button>
                </div>
            </div>
        </div>
    </div><!-- end of #crop-modal -->

    <!-- Camera Modal -->
    <div id="camera-modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close" data-dismiss="modal">&times;</span>
                    <h4 class="modal-title">Take a picture</h4>
                </div>
                <div class="modal-body">
                    <div class="camera-preview"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left camera-hide" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success camera-capture">Take picture</button>
                </div>
            </div>
        </div>
    </div><!-- end of #camera-modal -->

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timeago/1.5.2/jquery.timeago.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.0/cropper.min.js"></script>

    <script src="assets/js/filepicker.js"></script>
    <script src="assets/js/filepicker-ui.js"></script>
    <script src="assets/js/filepicker-drop.js"></script>
    <script src="assets/js/filepicker-crop.js"></script>
    <script src="assets/js/filepicker-camera.js"></script>
    <script src="assets/js/image-compressor.js"></script>

    <script src="assets/js/piexif.js"></script>
 

    <script>
        /*global $*/

        function blobToDataURL(blob, callback) {
            var a = new FileReader();
            a.onload = function(e) {callback(e.target.result);}
            a.readAsDataURL(blob);
        }

        //**dataURL to blob**
        function dataURLtoBlob(dataurl) {
            var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
                bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
            while(n--){
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new Blob([u8arr], {type:mime});
        }

        $('#filepicker').filePicker({
            url: 'uploader/index.php',
            ui: {
                autoUpload: false
            },
            plugins: ['ui', 'drop', 'camera', 'crop']
        })
        .on('add.filepicker', function (e, dt) {
            const file = dt.files[0];
            if ( file == null)
                return;
            if (file.type == 'image/jpeg')
            {            
                var reader = new FileReader();
                var exifStr = '';
                reader.onload = function(e) {
                    let exifData = piexif.load(e.target.result);
                    console.log(exifData);
                    exifStr = piexif.dump(exifData);
                    console.log(exifStr);
                };
                reader.readAsDataURL(file);
                console.log(file);
                new ImageCompressor(file, {
                    quality: .6,
                    success(result) {
                        blobToDataURL(result, function(dataurl){
                            var inserted = piexif.insert(exifStr, dataurl);
                            blob = dataURLtoBlob(inserted);
                            let tempFile = new File([blob], result.name, {type: result.type, lastModified: Date.now()});
                            tempFile.autoUpload = file.autoUpload;
                            tempFile.context = file.context;
                            tempFile.extension = file.extension;
                            tempFile.imageFile = file.imageFile;
                            tempFile.extension = file.extension;
                            tempFile.timeFormatted = file.timeFormatted;
                            console.log(tempFile);
                            dt.files[0] = tempFile;                        
                        });
                    },
                    error(e) {
                      console.log(e.message);
                    },
                });
            }else{
                new ImageCompressor(file, {
                    quality: .6,
                    success(result) {
                      let tempFile = new File([result], result.name, {type: result.type, lastModified: Date.now()});
                      tempFile.autoUpload = file.autoUpload;
                      tempFile.context = file.context;
                      tempFile.extension = file.extension;
                      tempFile.imageFile = file.imageFile;
                      tempFile.extension = file.extension;
                      tempFile.timeFormatted = file.timeFormatted;
                      dt.files[0] = tempFile;
                    },
                    error(e) {
                      console.log(e.message);
                    },
                });                
            }
        });


        // Replace timeago strings.
        if ($.fn.timeago) {
            $.timeago.settings.strings = $.extend({}, $.timeago.settings.strings , {
                seconds: 'few seconds', minute: 'a minute',
                hour: 'an hour', hours: '%d hours', day: 'a day',
                days: '%d days', month: 'a month', year: 'a year'
            });
        }
    </script>

    <!-- Upload Template -->
    <script type="text/x-tmpl" id="uploadTemplate">
        <tr class="upload-template">
            <td class="column-preview">
                <div class="preview">
                    <span class="fa file-icon-{%= o.file.extension %}"></span>
                </div>
            </td>
            <td class="column-name">
                <p class="name">{%= o.file.name %}</p>
                <span class="text-danger error">{%= o.file.error || '' %}</span>
            </td>
            <td colspan="2">
                <p>{%= o.file.sizeFormatted || '' %}</p>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active"></div>
                </div>
            </td>
            <td>
                {% if (!o.file.autoUpload && !o.file.error) { %}
                    <a href="#" class="action action-primary start" title="Upload">
                        <i class="fa fa-arrow-circle-o-up"></i>
                    </a>
                {% } %}
                <a href="#" class="action action-warning cancel" title="Cancel">
                    <i class="fa fa-ban"></i>
                </a>
            </td>
        </tr>
    </script><!-- end of #uploadTemplate -->

    <!-- Download Template -->
    <script type="text/x-tmpl" id="downloadTemplate">
        {% o.timestamp = function (src) {
            return (src += (src.indexOf('?') > -1 ? '&' : '?') + new Date().getTime());
        }; %}
        <tr class="download-template">
            <td class="column-preview">
                <div class="preview">
                    {% if (o.file.versions && o.file.versions.thumb) { %}
                        <a href="{%= o.file.url %}" target="_blank">
                            <img src="{%= o.timestamp(o.file.versions.thumb.url) %}" width="64" height="64"></a>
                        </a>
                    {% } else { %}
                        <span class="fa file-icon-{%= o.file.extension %}"></span>
                    {% } %}
                </div>
            </td>
            <td class="column-name">
                <p class="name">
                    {% if (o.file.url) { %}
                        <a href="{%= o.file.url %}" target="_blank">{%= o.file.name %}</a>
                    {% } else { %}
                        {%= o.file.name %}
                    {% } %}
                </p>
                {% if (o.file.error) { %}
                    <span class="text-danger">{%= o.file.error %}</span>
                {% } %}
            </td>
            <td class="column-size"><p>{%= o.file.sizeFormatted %}</p></td>
            <td class="column-date">
                {% if (o.file.time) { %}
                    <time datetime="{%= o.file.timeISOString() %}">
                        {%= o.file.timeFormatted %}
                    </time>
                {% } %}
            </td>
            <td>
                {% if (o.file.imageFile && !o.file.error) { %}
                    <a href="#" class="action action-primary crop" title="Crop">
                        <i class="fa fa-crop"></i>
                    </a>
                {% } %}
                {% if (o.file.error) { %}
                    <a href="#" class="action action-warning cancel" title="Cancel">
                        <i class="fa fa-ban"></i>
                    </a>
                {% } else { %}
                    <a href="#" class="action action-danger delete" title="Delete">
                        <i class="fa fa-trash-o"></i>
                    </a>
                {% } %}
            </td>
        </tr>
    </script><!-- end of #downloadTemplate -->

    <!-- Pagination Template -->
    <script type="text/x-tmpl" id="paginationTemplate">
        {% if (o.lastPage > 1) { %}
            <ul class="pagination pagination-sm">
                <li {% if (o.currentPage === 1) { %} class="disabled" {% } %}>
                    <a href="#!page={%= o.prevPage %}" data-page="{%= o.prevPage %}" title="Previous">&laquo;</a>
                </li>

                {% if (o.firstAdjacentPage > 1) { %}
                    <li><a href="#!page=1" data-page="1">1</a></li>
                    {% if (o.firstAdjacentPage > 2) { %}
                       <li class="disabled"><a>...</a></li>
                    {% } %}
                {% } %}

                {% for (var i = o.firstAdjacentPage; i <= o.lastAdjacentPage; i++) { %}
                    <li {% if (o.currentPage === i) { %} class="active" {% } %}>
                        <a href="#!page={%= i %}" data-page="{%= i %}">{%= i %}</a>
                    </li>
                {% } %}

                {% if (o.lastAdjacentPage < o.lastPage) { %}
                    {% if (o.lastAdjacentPage < o.lastPage - 1) { %}
                        <li class="disabled"><a>...</a></li>
                    {% } %}
                    <li><a href="#!page={%= o.lastPage %}" data-page="{%= o.lastPage %}">{%= o.lastPage %}</a></li>
                {% } %}

                <li {% if (o.currentPage === o.lastPage) { %} class="disabled" {% } %}>
                    <a href="#!page={%= o.nextPage %}" data-page="{%= o.nextPage %}" title="Next">&raquo</a>
                </li>
            </ul>
        {% } %}
    </script><!-- end of #paginationTemplate -->

</body>
</html>
