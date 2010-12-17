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
					<div id="babel-second-row">
						<div>[[%babel.link_translation_manually]]</div>
						<input type="text" name="babel-link-target" class="x-form-text" value="[[+resourceId]]" />
						<input type="submit" name="babel-link" value="[[%babel.save]]" class="x-btn button" />
					</div>
				`]]
			</form>
		</div>
	`]]
</div>