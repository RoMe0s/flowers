<script>
    CKEDITOR.config.allowedContent = true;
    CKEDITOR.dtd.a.div = 1;
    CKEDITOR.dtd.h2.hr = 1;
    CKEDITOR.replace('{!! $id !!}',
        {
            filebrowserImageBrowseUrl: '{!! route('admin.elfinder.ckeditor4') !!}'
        }
    );
</script>