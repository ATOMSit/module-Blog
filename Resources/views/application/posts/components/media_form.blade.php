@push('styles')
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Cropper.js</title>
    <link rel="stylesheet" href="{{asset('application/cropper/cropper.css')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
@endpush

@push('scripts')
    <script src="{{asset('application/cropper/cropper.js')}}"></script>
    <script type="application/javascript">
        var div_media_picture = document.getElementById("media_picture");
        var div_media_picture_present = document.getElementById("media_picture_present");

        var button_media_add = document.getElementById("button_media_add");
        var button_media_cancel = document.getElementById("button_media_cancel");
        var button_media_delete = document.getElementById("button_media_delete");
        var button_media_restore = document.getElementById("button_media_restore");

        var media_delete = false;

        function file_input_null() {
            document.getElementById("input_cropper").value = "";
        }

        function change_delete_statut() {
            if (document.getElementById("input_media_delete").checked === true) {
                media_delete = false;
                document.getElementById("input_media_delete").checked = false;
            } else {
                media_delete = true;
                document.getElementById("input_media_delete").checked = true;
            }
        }

        function show_cropper() {
            div_media_picture.style.display = "flex";
            button_media_add.firstChild.data = "MODIFIER L'IMAGE";
            button_media_add.style.display = 'flex';
            button_media_cancel.style.display = 'flex';
        }

        function noshow_cropper() {
            // Si la personne a supprimer le media déjà present
            if (media_delete === true) {
                file_input_null();
                div_media_picture.style.display = "none";
                div_media_picture_present.style.display = "none";

                button_media_add.firstChild.data = "AJOUTER UN MEDIA";
                button_media_add.style.display = 'flex';
                button_media_cancel.style.display = 'none';
                button_media_delete.style.display = 'none';
                button_media_restore.style.display = 'flex';
            } else {
                // Si la personne n'a pas encore supprimé le média présent
                if ($("#media_picture_present").length !== 0) {
                    div_media_picture.style.display = "none";
                    div_media_picture_present.style.display = "flex";

                    button_media_add.firstChild.data = "AJOUTER UN MEDIA";
                    button_media_add.style.display = 'none';
                    button_media_cancel.style.display = 'none';
                    button_media_delete.style.display = 'flex';
                    button_media_restore.style.display = 'none';
                } else {
                    // Si c'est pour une création
                    file_input_null();
                    div_media_picture.style.display = "none";
                    button_media_add.firstChild.data = "AJOUTER UN MEDIA";
                    button_media_add.style.display = 'flex';
                    button_media_cancel.style.display = 'none';
                }
            }
        }

        function transition_media_change() {
            if (document.getElementById("button_media_add").firstChild.data === "MODIFIER L'IMAGE") {
                document.getElementById("media_picture").style.display = "flex";
                document.getElementById("button_media_cancel").style.display = "none";
            } else {
                document.getElementById("media_picture").style.display = "none";
                document.getElementById("button_media_cancel").style.display = "none";
            }
        }


        function add_media() {
            $(input_cropper).click();
        }

        function cancel_media() {
            noshow_cropper();
        }

        function delete_media() {
            change_delete_statut();
            noshow_cropper();
        }

        function restore_media() {
            change_delete_statut();
            noshow_cropper();
        }
    </script>

    <script type="application/javascript">
        document.getElementById("input_cropper").style.display = "none";
        var isInitialized = false;
        var cropper = '';
        var file = '';
        var _URL = window.URL || window.webkitURL;
        $(document).ready(function () {
            // Si une image est presente alors
            noshow_cropper();
            $("#input_cropper").change(function (e) {
                transition_media_change();
                if (file = this.files[0]) {
                    var oFReader = new FileReader();
                    oFReader.readAsDataURL(file);
                    oFReader.onload = function () {
                        var cropper_image = $("#cropper_image");
                        cropper_image.attr('src', this.result);
                        cropper_image.addClass('ready');
                        if (isInitialized === true) {
                            cropper.destroy();
                        }
                        initCropper();
                    }
                }
            });
        });

        function initCropper() {
            var vEl = document.getElementById('cropper_image');
            cropper = new Cropper(vEl, {
                viewMode: 2,
                aspectRatio: 16 / 9,
                minContainerWidth: 640,
                minContainerHeight: 360,
                maxContainerWidth: 640,
                maxContainerHeight: 360,
                crop(event) {
                    document.getElementById("picture[x]").value = (event.detail.x);
                    document.getElementById("picture[y]").value = (event.detail.y);
                    document.getElementById("picture[width]").value = (event.detail.width);
                    document.getElementById("picture[height]").value = (event.detail.height);
                },
                ready: function (e) {
                    var cropper = this.cropper;
                    cropper.zoomTo(0);
                    var imageData = cropper.getImageData();
                    show_cropper();
                }
            });
            isInitialized = true;
        }
    </script>
@endpush

<div class="kt-wizard-v1__content" data-ktwizard-type="step-content">
    <div class="kt-heading kt-heading--md">
        Ajouter une image d'illustration de votre article
    </div>
    {!! form_row($form_post->picture->x,$options=['label_show'=>false,'attr'=>['id'=>'picture[x]']]) !!}
    {!! form_row($form_post->picture->y,$options=['label_show'=>false,'attr'=>['id'=>'picture[y]']]) !!}
    {!! form_row($form_post->picture->width,$options=['label_show'=>false,'attr'=>['id'=>'picture[width]']]) !!}
    {!! form_row($form_post->picture->height,$options=['label_show'=>false,'attr'=>['id'=>'picture[height]']]) !!}
    <div class="kt-form__section kt-form__section--first">
        {!! form_row($form_post->input_cropper,$options=['label_show'=>false,'attr'=>['id'=>'input_cropper']]) !!}
        <div class="kt-wizard-v1__form">
            @if(isset($post) and $post->getFirstMedia('cover') !== null)
                @if(strpos($post->getFirstMedia('cover')->mime_type, 'image') !== false)
                    <div class="row justify-content-center" id="media_picture_present" style="display: flex">
                        <div class="col-xl-12">
                            {{$post->getFirstMedia('cover')}}
                        </div>
                    </div>
                    {!! form_row($form_post->input_media_delete,$options=['label_show'=>false,'attr'=>['id'=>'input_media_delete']]) !!}
                    <div class="row justify-content-center" id="media_picture" style="display: none">
                        <div class="col-xl-8">
                            <div class="form-group">
                                <img id="cropper_image" src="" alt="Picture"
                                     style="width: 640px; height: 288px; transform: none;">
                            </div>
                        </div>

                    </div>
                @else
                    <div class="row justify-content-center" id="media_picture" style="display: none">
                        <div class="col-xl-8">
                            <div class="form-group">
                                <img id="cropper_image" src="" alt="Picture"
                                     style="width: 640px; height: 288px; transform: none;">
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="row justify-content-center" id="media_picture" style="display: none">
                    <div class="col-xl-8">
                        <div class="form-group">
                            <img id="cropper_image" src="" alt="Picture"
                                 style="width: 640px; height: 288px; transform: none;">
                        </div>
                    </div>
                </div>
            @endif

            <div class="row justify-content-center">
                <button type="button" id="button_media_add" onclick="add_media()"
                        class="btn btn-font-lg  btn-md btn-primary btn-pill"
                        style="display: flex">
                    AJOUTER UN MEDIA
                </button>
                &nbsp;
                <button type="button" id="button_media_cancel" onclick="cancel_media()"
                        class="btn bbtn-font-lg btn-md btn-danger btn-pill"
                        style="display: none">
                    ANNULER
                </button>
                &nbsp;
                <button type="button" id="button_media_delete" onclick="delete_media()"
                        class="btn bbtn-font-lg btn-md btn-danger btn-pill"
                        style="display: none">
                    SUPPRIMER
                </button>
                &nbsp;
                <button type="button" id="button_media_restore" onclick="restore_media()"
                        class="btn btn-font-lg btn-md btn-warning btn-pill"
                        style="display: none">
                    RESTORER
                </button>
            </div>
        </div>
    </div>
</div>