
import { __ } from '@wordpress/i18n';
import {
	registerBlockType,
} from '@wordpress/blocks';
import {
	RichText,
	MediaUpload,
} from '@wordpress/block-editor';
import { Button } from '@wordpress/components';

registerBlockType( 'pettit-realestate/call-to-action', {
	title: __( 'Call to Action', 'pettit-realestate' ),
	icon: 'index-card',
	category: 'layout',
	attributes: {
		title: {
			type: 'array',
			source: 'children',
			selector: 'span',
		},
		mediaID: {
			type: 'number',
		},
		mediaURL: {
			type: 'string',
			source: 'attribute',
			selector: 'img',
			attribute: 'src',
		},
		// ingredients: {
		// 	type: 'array',
		// 	source: 'children',
		// 	selector: '.ingredients',
		// },
		// instructions: {
		// 	type: 'array',
		// 	source: 'children',
		// 	selector: '.steps',
		// },
	},
	example: {
		attributes: {
			title: __( 'Chocolate Chip Cookies', 'pettit-realestate' ),
			mediaURL: 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f1/2ChocolateChipCookies.jpg/320px-2ChocolateChipCookies.jpg',
			// ingredients: [
			// 	__( 'flour', 'pettit-realestate' ),
			// 	__( 'sugar', 'pettit-realestate' ),
			// 	__( 'chocolate', 'pettit-realestate' ),
			// 	'💖'
			// ],
			// instructions: [
			// 	__( 'Mix', 'pettit-realestate' ),
			// 	__( 'Bake', 'pettit-realestate' ),
			// 	__( 'Enjoy', 'pettit-realestate' ),
			// ],
		},
	},
	edit: ( props ) => {
		const {
			className,
			attributes: {
				title,
				mediaID,
				mediaURL,
				// ingredients,
				// instructions,
			},
			setAttributes,
		} = props;
		const onChangeTitle = ( value ) => {
			setAttributes( { title: value } );
		};

		const onSelectImage = ( media ) => {
			setAttributes( {
				mediaURL: media.url,
				mediaID: media.id,
			} );
		};
		// const onChangeIngredients = ( value ) => {
		// 	setAttributes( { ingredients: value } );
		// };

		// const onChangeInstructions = ( value ) => {
		// 	setAttributes( { instructions: value } );
		// };

		return (
			<div className={ className }>
				<RichText
					tagName="span"
					placeholder={ __( 'Top Text (optional)', 'pettit-realestate' ) }
					value={ title }
					onChange={ onChangeTitle }
				/>
				<div className="recipe-image">
					<MediaUpload
						onSelect={ onSelectImage }
						allowedTypes="image"
						value={ mediaID }
						render={ ( { open } ) => (
							<Button className={ mediaID ? 'image-button' : 'button button-large' } onClick={ open }>
								{ ! mediaID ? __( 'Upload Image', 'pettit-realestate' ) : <img src={ mediaURL } alt={ __( 'Upload Call to Action Image', 'pettit-realestate' ) } /> }
							</Button>
						) }
					/>
				</div>

			</div>
		);
	},
	save: ( props ) => {
		const {
			className,
			attributes: {
				title,
				mediaURL
			},
		} = props;
		return (
			<div className={ className }>
				<RichText.Content tagName="span" value={ title } />

				{
					mediaURL && (
						<img className="recipe-image" src={ mediaURL } alt={ __( 'Recipe Image', 'pettit-realestate' ) } />
					)
				}


			</div>
		);
	},
} );
