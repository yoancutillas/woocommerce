/**
 * External dependencies
 */
import { FormSection, WooProductFieldItem } from '@woocommerce/components';

/**
 * Internal dependencies
 */
import './product-section-layout.scss';

type ProductSectionLayoutProps = {
	title: string;
	description: string | JSX.Element;
	className?: string;
};

export const ProductSectionLayout: React.FC< ProductSectionLayoutProps > = ( {
	title,
	description,
	className,
	children,
} ) => {
	return (
		<FormSection
			title={ title }
			description={ description }
			className={ className }
		>
			{ children }
		</FormSection>
	);
};
