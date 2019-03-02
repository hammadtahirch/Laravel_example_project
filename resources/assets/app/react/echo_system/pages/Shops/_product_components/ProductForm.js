import React, {Component} from 'react';
import {connect} from 'react-redux';
import moment from 'moment';
import 'react-toastify/dist/ReactToastify.css';
import {ToastContainer, toast} from 'react-toastify';
import ValidationErrors from "../../sub_components/ValidationErrors";
import Dropzone from "react-dropzone";
import classNames from 'classnames';

const queryString = require('query-string');

class ProductForm extends Component {

    /**
     * constructor
     * @param props
     */
    constructor(props) {
        super(props);

        this.state = {
            product: {
                id: '',
                title: '',
                description: '',
                price: ''
            }
        };
    }

    /**
     * componentDidMount [react default life cycle functions]
     */
    componentDidMount() {
    }

    /**
     * handle drop zone drag and drop event
     *
     * @param e
     */
    onDrop(e) {
        e.forEach(file => {
            const reader = new FileReader();
            reader.onload = () => {
                const fileAsBinaryString = reader.result;
                console.log(fileAsBinaryString);
            };

            reader.onabort = () => console.log('file reading was aborted');
            reader.onerror = () => console.log('file reading has failed');
            reader.readAsDataURL(file);
        })
    }

    /**
     * render [DOM render ]
     */
    render() {
        return (
            <div>
                <ToastContainer/>
                <div className="card mb-15">
                    <div className="card-body">
                        <h5>Product</h5>
                        <hr/>
                        <form>
                            <div className="row ">
                                <div className="col-md-8 mb-2">
                                    <Dropzone onDrop={(e) => this.onDrop(e)}>
                                        {({getRootProps, getInputProps, isDragActive}) => {
                                            return (
                                                <div
                                                    {...getRootProps()}
                                                    className={classNames('dropzone model-drop-zone', {'dropzone--isActive': isDragActive})}>
                                                    <input
                                                        className="form-control" {...getInputProps()} />
                                                    {
                                                        isDragActive ?
                                                            <span>Drop here</span> :
                                                            <span>Try dropping some files here,</span>
                                                    }
                                                </div>
                                            )
                                        }}
                                    </Dropzone>
                                </div>
                                <div className="col-md-4 mb-2">
                                    {/*<img*/}
                                    {/*src={(this.state.shop.dataUrl) ? this.state.shop.dataUrl : (this.state.shop.upload) ? this.state.shop.upload.storage_url : require('../../../assets/img/placeholder-image.png')}*/}
                                    {/*className="model-drop-zone-preview"/>*/}
                                </div>
                                <div className="col-md-12 mb-3">
                                    <label>Title<span>*</span></label>
                                    <input type="text" className="form-control"
                                           name="title"/>
                                </div>
                                <div className="col-md-12 mb-3">
                                    <label>Description <span>*</span></label>
                                    <input type="text" className="form-control"
                                           name="description"/>
                                </div>
                                <div className="col-md-6 mb-3">
                                    <label>price <span>*</span></label>
                                    <input type="text" className="form-control"
                                           name="city"/>
                                </div>
                                <div className="col-md-6 mb-3">
                                    <label>status <span>*</span></label>
                                    <input type="text" className="form-control"
                                           name="province"/>
                                </div>
                                <div className="col-md-12 mb-3">
                                    <button type="button"
                                            className="btn btn-outline-dark font-14 mb-30 pull-right">
                                        Create
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

        );
    }
}

/**
 * mapStateToProp
 * @param  state
 * @return states
 */
function mapStateToProp(state) {
    console.log(state);
    return ({
        // fetch_shop_product_props: state.product.fetch_products,
        error: state.error.error,
    })
}

/**
 * mapDispatchToProp
 * @param  dispatch
 * @return dispatches
 */
function mapDispatchToProp(dispatch) {
    return ({
        // fetchProductById: (shop_id, product_id) => {
        //     dispatch(_fetchProductById(shop_id, product_id));
        // },
        // fetchProducts: (shop_id, params) => {
        //     dispatch(_fetchAllProduct(shop_id, params));
        // },
        // saveProduct: (shop_id, params) => {
        //     dispatch(_saveProduct(shop_id, params));
        // },
        // deleteProduct: (shop_id, params) => {
        //     dispatch(_deleteProduct(shop_id, params));
        // },
        // duplicateProduct: (shop_id, params) => {
        //     dispatch(_duplicateProduct(shop_id, params));
        // },

    })
}

export default connect(mapStateToProp, mapDispatchToProp)(ProductForm);
