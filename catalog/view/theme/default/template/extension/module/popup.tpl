
<?php foreach ($banners as $banner): ?>
    <a class="fancybox" style="display: none" name="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" /></a>
<?php endforeach;?>

<script type="text/javascript">
    $(document).ready(function() {
        $(".fancybox").fancybox({
            padding : 0,
            // API options
            afterShow: function () {
                if($(this.element).attr('name') !='') {
                    $(".fancybox img")
                    // first method
                            .wrap("<a href='" + $(this.element).attr('name') + "' target='_blank' />")
                            // second method
                            .on("click", function () {
                                $.fancybox.close(true);
                            });
                    $(".fancybox").eq(0).removeClass('fancybox');
                }
            }
        });

        setTimeout(function(){
            // Launch fancyBox on first element
            $(".fancybox").eq(0).trigger('click');

        }, <?php echo $delay ?>);
    });
</script>