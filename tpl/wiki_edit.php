<?php if (isset($preview)):?>
<div class="draft"><?php echo $preview; ?></div>
<small>This is only preview.</small>
<hr>
<?php endif; ?>
<form action="<?php echo $action; ?>" method="post">
<textarea name="text" rows="15" style="width: 100%">
<?php echo $content; ?>
</textarea><br>
<input type="submit" name="save" value="Save" class="btn btn-primary">
<input type="submit" name="prev" value="Preview" class="btn"> 
Comment: 
<input name="comment">
</form>