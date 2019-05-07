@push('styles')
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Cropper.js</title>
    <link rel="stylesheet" href="{{asset('application/cropper/cropper.css')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
@endpush

@push('scripts')
    <script src="{{asset('application/cropper/cropper.js')}}"></script>
    <script>
        window.addEventListener('DOMContentLoaded', function () {


            var div_picture = document.getElementById("media_picture");

            var button_cancel = document.getElementById("media_cancel");
            var button_restore= document.getElementById("media_restore");
            var button_delete = document.getElementById("media_delete");


            var widht = "1";
            var height = "1";
            var image = document.querySelector('#cropper_image');
            var cropper = new Cropper(image, {
                viewMode: 2,
                aspectRatio: widht / height,
                crop(event) {
                    document.getElementById("picture[x]").value = (event.detail.x);
                    document.getElementById("picture[y]").value = (event.detail.y);
                    document.getElementById("picture[width]").value = (event.detail.width);
                    document.getElementById("picture[height]").value = (event.detail.height);
                },
            });
            $('input[name="file"]').change(function (e) {
                var file = e.target.files[0];
                var type = e.target.files[0].type;
                if (type.includes('image')) {
                    var file = document.querySelector('input[name=file]').files[0];
                    var reader = new FileReader();
                    reader.addEventListener("load", function () {
                        div_picture.style.display = "flex";
                        button_cancel.style.display = "block";
                        cropper.replace(reader.result);
                    }, false);
                    if (file) {
                        reader.readAsDataURL(file);
                    }
                }
            });
            $("#media_cancel").on("click", function () {
                div_picture.style.display = "none";
                button_cancel.style.display = "none";
                document.getElementById("file").value = "";
            });
            $("#media_delete").on("click", function () {
                div_picture.style.display = "none";
                button_cancel.style.display = "none";
            });
        });
    </script>
@endpush

{!! form_row($form_post->file) !!}

<div class="kt-wizard-v1__form">


    <div class="row justify-content-center" id="media_picture" style="display: none">
        <div class="col-xl-8">
            <div class="form-group">
                <img id="cropper_image" src="{{asset('application/media/users/100_1.jpg')}}" alt="Picture"
                     style="max-height: 300px;max-width: 300px;min-height: 300px;min-width: 300px">
            </div>
        </div>
        <div class="col-xl-4">
            {!! form_row($form_post->picture->x,$options=['label_show'=>false,'attr'=>['id'=>'picture[x]']]) !!}
            {!! form_row($form_post->picture->y,$options=['label_show'=>false,'attr'=>['id'=>'picture[y]']]) !!}
            {!! form_row($form_post->picture->width,$options=['label_show'=>false,'attr'=>['id'=>'picture[width]']]) !!}
            {!! form_row($form_post->picture->height,$options=['label_show'=>false,'attr'=>['id'=>'picture[height]']]) !!}
        </div>
    </div>



    <div class="row justify-content-center">
        <button type="button" id="media_cancel" class="btn btn-danger btn-pill" style="display: none">
            Annuler
        </button>
        &nbsp;
        <button type="button" id="media_restore" class="btn btn-primary btn-pill" style="display: none">
            Restaurer
        </button>
        &nbsp;
        <button type="button" id="media_delete" class="btn btn-warning btn-pill" style="display: none">
            Supprimer
        </button>
    </div>


</div>