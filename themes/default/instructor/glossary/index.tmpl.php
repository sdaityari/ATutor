<form id="words-form" name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<table class="data" summary="" rules="cols" style="width: 90%;" id="glossary-terms">
<thead>
<tr>
	<th scope="col">&nbsp;</th>
	<th scope="col"><?php echo _AT('glossary_term'); ?></th>
	<th scope="col"><?php echo _AT('glossary_definition'); ?></th>
	<th scope="col"><?php echo _AT('glossary_related'); ?></th>
</tr>
</thead>
<tfoot>
<tr id="buttons-row">
	<td colspan="4"><input type="button" name="add" value="<?php echo _AT('add'); ?>" onclick="ATutor.glossary.showForm();"><input type="button" name="edit" value="<?php echo _AT('edit'); ?>" /> <input type="button" name="delete" value="<?php echo _AT('delete'); ?>" onclick="ATutor.glossary.deleteItem();" /> </td>
</tr>
</tfoot>
<tbody>

<?php if(!empty($this->gloss_results_row)):?>
	<?php foreach($this->gloss_results_row as $row): ?>
		<tr onmousedown="document.form['m<?php echo $row['word_id']; ?>'].checked = true; rowselect(this);" id="r_<?php echo $row['word_id']; ?>">
			<td valign="top" width="10"><input type="radio" name="word_id" value="<?php echo $row['word_id']; ?>" id="m<?php echo $row['word_id']; ?>" /></td>
			<td valign="top"><label for="m<?php echo $row['word_id']; ?>"><?php echo AT_print($row['word'], 'glossary.word'); ?></label></td>
			<td style="whitespace:nowrap;"><?php echo AT_print($row['definition'], 'glossary.definition'); ?></td>
		    <td valign="top"><?php if(!empty($row['related_word_id'])):?>
			<?php
			echo AT_print($row['related_word'], 'glossary.word'); ?>
			<?php endif; ?></td>
		</tr>
	
	<?php endforeach;?>
<?php endif; ?>

</tbody>
</table>
</form>

<div class="input-form" id="glossary-form" style="display:none;">
    <div class="row">
        <span class="required" title="<?php echo _AT('required_field'); ?>">*</span><label for="term"><?php echo _AT('glossary_term');  ?></label><br />
            <input type="text" name="term" id="glossary-form-name" size="30" />
</div>

<div class="row">
<span class="required" title="<?php echo _AT('required_field'); ?>">*</span><label for="definition"><?php echo _AT('glossary_definition');  ?></label><br />
<textarea name="definition" id="glossary-form-definition" class="formfield" cols="55" rows="7" style="width:90%;"></textarea>
</div>

<div class="row">
<?php echo _AT('glossary_related');  ?><br />
    <select id="glossary-form-related" name="related_term">
    <option value="0"></option>
    </select>
</div>

<div class="row buttons">
<input type="button" name="submit" value="<?php echo _AT('save'); ?>" accesskey="s" onclick="ATutor.glossary.confirmSubmit();" />
<input type="button" name="cancel" value="<?php echo _AT('cancel'); ?>" onclick="ATutor.glossary.hideForm();" />
</div>

</div>
