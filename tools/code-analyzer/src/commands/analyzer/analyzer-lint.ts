/**
 * External dependencies
 */
import { Command } from '@commander-js/extra-typings';
import { Logger } from 'cli-core/src/logger';
import { join } from 'path';

/**
 * Internal dependencies
 */
import { scanForChanges } from '../../lib/scan-changes';
import {
	printDatabaseUpdates,
	printHookResults,
	printSchemaChange,
	printTemplateResults,
} from '../../print';

const program = new Command()
	.command( 'lint' )
	.argument(
		'<compare>',
		'GitHub branch/tag/commit hash to compare against the base branch/tag/commit hash.'
	)
	.argument(
		'<sinceVersion>',
		'Specify the version used to determine which changes are included (version listed in @since code doc).'
	)
	.option(
		'-b, --base <base>',
		'GitHub base branch/tag/commit hash.',
		'trunk'
	)
	.option(
		'-s, --source <source>',
		'Git repo url or local path to a git repo.',
		join( process.cwd(), '../../' )
	)
	.option(
		'-o, --outputStyle <outputStyle>',
		'Output style for the results. Options: github, cli. Github output will set the results as an output variable for Github actions.',
		'cli'
	)
	.option(
		'-ss, --skipSchemaCheck',
		'Skip the schema check, enable this if you are not analyzing WooCommerce'
	)
	.action( async ( compare, sinceVersion, options ) => {
		const { skipSchemaCheck = false, source, base, outputStyle } = options;

		// const changes = await scanForChanges(
		// 	compare,
		// 	sinceVersion,
		// 	skipSchemaCheck,
		// 	source,
		// 	base,
		// 	outputStyle
		// );

		// if ( changes.templates.size ) {
		printTemplateResults(
			[
				{
					filePath: 'path/to/file.php',
					code: 'code',
					message: 'message',
				},
			],
			outputStyle,
			'TEMPLATES',
			Logger.notice
		);
		// }

		// if ( changes.hooks.size ) {
		printHookResults(
			[
				{
					filePath: 'Hello',
					name: 'World',
					description: 'Description',
					hookType: 'hook type',
					changeType: 'new',
					version: '1.2.3',
					ghLink: 'https://example.com',
				},
			],
			outputStyle,
			'HOOKS',
			Logger.notice
		);
		// }

		// if ( changes.schema.filter( ( s ) => ! s.areEqual ).length ) {
		printSchemaChange(
			[
				{
					areEqual: true,
					method: 'Hello World!',
					name: 'name',
					description: 'description',
					base: 'base',
					compare: 'compare',
				},
			],
			sinceVersion,
			outputStyle,
			Logger.notice
		);
		// }

		// if ( changes.db ) {
		printDatabaseUpdates(
			{ updateFunctionName: 'Hello', updateFunctionVersion: 'World!' },
			outputStyle,
			Logger.notice
		);
		// }
	} );

program.parse( process.argv );
