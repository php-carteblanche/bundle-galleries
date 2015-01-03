<?php
/**
 * This file is part of the CarteBlanche PHP framework.
 *
 * (c) Pierre Cassat <me@e-piwi.fr> and contributors
 *
 * License Apache-2.0 <http://github.com/php-carteblanche/carteblanche/blob/master/LICENSE>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>

<?php if (!empty($flash_message)) : ?>
		<<?php echo( !empty($flash_message_class) ? $flash_message_class : 'ok' ); ?>>
		<?php echo $flash_message; ?>
		</<?php echo( !empty($flash_message_class) ? $flash_message_class : 'ok' ); ?>>
<?php endif; ?>

<?php echo $output; ?>

