type ProductFieldLayoutProps = {
	fieldName: string;
	categoryName: string;
};

export const ProductFieldLayout: React.FC< ProductFieldLayoutProps > = ( {
	children,
} ) => {
	return <div className="product-field-layout">{ children }</div>;
};
