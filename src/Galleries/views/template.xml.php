<?php
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>

<?php if (!empty($flash_message)) : ?>
		<<?php echo( !empty($flash_message_class) ? $flash_message_class : 'ok' ); ?>>
		<?php echo $flash_message; ?>
		</<?php echo( !empty($flash_message_class) ? $flash_message_class : 'ok' ); ?>>
<?php endif; ?>

<?php echo $output; ?>

