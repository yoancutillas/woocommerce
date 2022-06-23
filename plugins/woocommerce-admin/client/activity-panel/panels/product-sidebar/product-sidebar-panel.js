/**
 * External dependencies
 */
import { Panel, TabPanel, PanelBody, PanelRow } from '@wordpress/components';

const ScrollableContainer = ( { children } ) => {
	return (
		<div
			style={ {
				width: '100%',
				height: '100vh',
				overflowY: 'auto',
				margin: 'auto',
				boxShadow: '0 0 0 1px #ddd inset',
				backgroundColor: '#fff',
			} }
		>
			{ children }
		</div>
	);
};

const Placeholder = ( { height = 200 } ) => {
	return <div style={ { background: '#ddd', height, width: '100%' } } />;
};

export const SetupTasksPanel = ( { query } ) => {
	return (
		<ScrollableContainer>
			<Panel header="My Panel">
				<TabPanel
					className="my-tab-panel"
					tabs={ [
						{
							name: 'tab1',
							title: 'Tab 1 title',
							className: 'tab-one',
							content: () => (
								<>
									<PanelBody
										initialOpen={ false }
										title="First Settings"
									>
										<PanelRow>
											<Placeholder height={ 250 } />
										</PanelRow>
									</PanelBody>
									<PanelBody
										title="Second Settings"
										initialOpen={ false }
									>
										<PanelRow>
											<Placeholder height={ 400 } />
										</PanelRow>
									</PanelBody>
								</>
							),
						},
						{
							name: 'tab2',
							title: 'Tab 2 title',
							className: 'tab-two',
							content: () => (
								<>
									<PanelBody title="Third Settings">
										<PanelRow>
											<Placeholder height={ 100 } />
										</PanelRow>
									</PanelBody>
									<PanelBody
										title="Forth Settings"
										initialOpen={ false }
									>
										<PanelRow>
											<Placeholder height={ 50 } />
										</PanelRow>
									</PanelBody>
								</>
							),
						},
					] }
				>
					{ ( tab ) => tab.content() }
				</TabPanel>
			</Panel>
		</ScrollableContainer>
	);
};

export default SetupTasksPanel;
