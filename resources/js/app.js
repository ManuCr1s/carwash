import './bootstrap';
import Swal from 'sweetalert2';
window.Swal = Swal;

import * as FilePond from 'filepond';

import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImageResize from 'filepond-plugin-image-resize';
import FilePondPluginImageTransform from 'filepond-plugin-image-transform';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

FilePond.registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateType,
    FilePondPluginFileValidateSize,
    FilePondPluginImageResize,
    FilePondPluginImageTransform
);

window.FilePond = FilePond;

document.addEventListener('livewire:initialized', () => {
    Livewire.on('swal', (data) => {
        Swal.fire(data[0]);
    });
});

document.addEventListener('DOMContentLoaded', () => {

    window.initFilePond = () => {

        document.querySelectorAll('.filepond').forEach(input => {

            if (input._pond) return;

            FilePond.create(input, {
                allowMultiple: false,
                acceptedFileTypes: ['image/*'],
                maxFileSize: '2MB',

                imageResizeTargetWidth: 800,
                imageTransformOutputQuality: 0.6,
            });

        });

    };

});

document.addEventListener('init-filepond', () => {
    setTimeout(() => {
        window.initFilePond();
    }, 100);
});