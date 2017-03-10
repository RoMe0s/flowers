<script>
    CKEDITOR.config.allowedContent = true;
    CKEDITOR.replace('{!! $id !!}',
        {
            filebrowserImageBrowseUrl: '{!! route('admin.elfinder.ckeditor4') !!}'
        }
    );
</script>