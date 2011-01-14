<div class="babel-language[[+className:notempty=` [[+className]]`]]">
	<a href="[[+resourceUrl]]">[[%babel.language_[[+cultureKey]]? &topic=`default` &namespace=`babel`]] ([[+contextKey]])</a>
	[[+showLayer:notempty=`
		<div class="babel-language-layer">
			<form method="post" action="[[+formUrl]]">
				<div id="babel-first-row">
					<input type="hidden" name="babel-context-key" value="[[+contextKey]]" />
					[[+showTranslateButton:notempty=`
						<input type="submit" name="babel-translate" value="[[%babel.create_translation]]" class="x-btn button" />
					`]]
					[[+showUnlinkButton:notempty=`
						<input type="submit" name="babel-unlink" value="[[%babel.unlink_translation]]" class="x-btn button" />
					`]]
				</div>
				[[+showSecondRow:notempty=`
					<hr />
					<div id="babel-second-row">
						<div>[[%babel.link_translation_manually]]</div>
						<div>
							<label>[[%babel.id_of_target]]</label>
							<input type="text" name="babel-link-target" class="x-form-text" value="[[+resourceId]]" />
						</div>
						<div>
							<input type="checkbox" name="babel-link-copy-tvs" value="1" checked="checked" id="babel-link-copy-tvs-[[+contextKey]]" />
							<label for="babel-link-copy-tvs-[[+contextKey]]">[[%babel.copy_tv_values]]</label>
						</div>
						<div>
							<input type="submit" name="babel-link" value="[[%babel.save]]" class="x-btn button" />
						</div>
					</div>
				`]]
			</form>
		</div>
	`]]
</div>