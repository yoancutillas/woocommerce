/**
 * External dependencies
 */
import { createElement } from '@wordpress/element';
import IsolatedBlockEditor, { OnLoad } from '@automattic/isolated-block-editor';

type RichTextEditorProps = {
	onError?: () => void;
	onLoad?: OnLoad;
	onSaveContent?: ( html: string ) => void;
};

export const RichTextEditor: React.FC< RichTextEditorProps > = ( {
	onLoad,
	onError = () => null,
	onSaveContent,
}: RichTextEditorProps ) => {
	return (
		<IsolatedBlockEditor
			className="woocommerce-rich-text-editor"
			settings={ {} }
			onSaveContent={ onSaveContent }
			onLoad={ onLoad }
			onError={ onError }
		/>
	);
};
