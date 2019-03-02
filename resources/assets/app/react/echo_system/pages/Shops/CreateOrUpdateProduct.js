import React, {Component} from 'react';
import {connect} from 'react-redux';
import {Redirect} from 'react-router'

import 'react-toastify/dist/ReactToastify.css';
import {getSession} from "../../../store/helper/helper";
import history from "../../../History";
import Header from "../../layout/Header";
import ShopNav from "./_nav/ShopNav";
import {
    _fetchProductById,
    _saveProduct,
} from "../../../store/action/action-product";
import Dropzone from "react-dropzone";
import classNames from 'classnames';
import ValidationErrors from "../sub_components/ValidationErrors";
import {convertDollarToCent} from "../../../store/helper/utill-helper";
import {ToastContainer} from "react-toastify";
import Loading from "../sub_components/Loading";

const queryString = require('query-string');

class CreateOrUpdateProduct extends Component {

    /**
     * constructor
     * @param props
     */
    constructor(props) {
        super(props);
        if (getSession('login') === null) {
            history.push('login');
        }

        this.state = {
            product: {
                id: '',
                title: '',
                description: '',
                price: '',
                status: 1,
                is_published: 1,
                dataUrl: '',
                upload: ''

            },
            status: [
                {
                    name: 'Active',
                    value: 1
                },
                {
                    name: 'Inactive',
                    value: 0
                }
            ],
            published: [
                {
                    name: 'Yes',
                    value: 1
                },
                {
                    name: 'No',
                    value: 0
                }
            ],
        };
    }

    /**
     * componentDidMount [react default life cycle functions]
     */
    componentDidMount() {
        if (typeof this.props.match.params.product_id !== 'undefined') {
            this.props.fetchProductById(this.props.match.params.id, this.props.match.params.product_id);
        }

    }

    /**
     *
     * @param nextProps
     * @param prevState
     */
    componentWillReceiveProps(nextProps, prevState) {
        if (nextProps.getSavedProductProps !== '') {
            this.setState({
                product: {
                    ...this.state,
                    id: nextProps.getSavedProductProps.product.id,
                    title: nextProps.getSavedProductProps.product.title,
                    description: nextProps.getSavedProductProps.product.description,
                    price: nextProps.getSavedProductProps.product.price,
                    status: nextProps.getSavedProductProps.product.status,
                    is_published: nextProps.getSavedProductProps.product.is_published,
                    upload: nextProps.getSavedProductProps.product.upload
                }
            })
            if (typeof nextProps.match.params.product_id === 'undefined') {
                history.push(nextProps.location.pathname + "/" + nextProps.getSavedProductProps.product.id);
            }
        }

        if (nextProps.getProductById !== '') {

            this.setState({
                product: {
                    ...this.state,
                    id: nextProps.getProductById.product.id,
                    title: nextProps.getProductById.product.title,
                    description: nextProps.getProductById.product.description,
                    price: nextProps.getProductById.product.price,
                    status: nextProps.getProductById.product.status,
                    is_published: nextProps.getProductById.product.is_published,
                    upload: nextProps.getProductById.product.upload
                }
            })
        }
    }

    /**
     * handleChange
     * @param var event
     */
    handleChange(event) {
        debugger;
        const {name, value} = event.target;
        const {product} = this.state;
        this.setState({
            product: {
                ...product,
                [name]: value
            }
        });
    }

    /**
     * store or update data in storage
     */
    save_product(shop_id, product) {
        product = {
            ...product,
            price: convertDollarToCent(product.price)
        }
        this.props.saveProduct(shop_id, product);
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
                const {product} = this.state;
                this.setState({
                    product: {
                        ...product,
                        dataUrl: fileAsBinaryString
                    }
                });
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
        const modalStyle = {
            modal: {
                maxWidth: "500px",
            }
        }
        return (

            <div>
                <ToastContainer/>
                <Loading/>
                <Header/>

                <div className="single-blog-wrapper">
                    <div className="container">
                        <div className="row justify-content-center">
                            <div className="col-3 col-md-3 mb-15 mt-15">
                                <div className="card">
                                    <div className="card-body">
                                        <ShopNav params={this.props.match}/>
                                    </div>
                                </div>

                            </div>
                            <div className="col-9 col-md-9">
                                <div className="regular-page-text mb-15 mt-15">
                                    <div className="regular-page-text">
                                        <div className="card">
                                            <div className="card-body">
                                                {(this.state.product.id === '') ?
                                                    <h5>Create Product</h5> :
                                                    <h5>Edit Product</h5>}

                                                <hr/>
                                                {(this.props.error !== "") &&
                                                <ValidationErrors validationErrors={this.props.error.data}
                                                                  statusCode={this.props.error.status}/>
                                                }
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
                                                            <img
                                                                src={(this.state.product.dataUrl) ? this.state.product.dataUrl : (this.state.product.upload) ? this.state.product.upload.storage_url : require('../../../assets/img/placeholder-image.png')}
                                                                className="model-drop-zone-preview"/>
                                                        </div>

                                                        <div className="col-md-12 mb-3">
                                                            <label>Title<span>*</span></label>
                                                            <input type="text" className="form-control"
                                                                   name="title" value={this.state.product.title}
                                                                   onChange={(e) => this.handleChange(e)}/>
                                                        </div>
                                                        <div className="col-md-12 mb-3">
                                                            <label>Description <span>*</span></label>
                                                            <textarea type="text" className="form-control"
                                                                      name="description"
                                                                      onChange={(e) => this.handleChange(e)}
                                                                      value={this.state.product.description}>
                                                                </textarea>
                                                        </div>

                                                        <div className="col-md-6 mb-3">
                                                            <label>Price <span>*</span></label>
                                                            <div className="input-group mb-3">
                                                                <div className="input-group-prepend">
                                                                    <span className="input-group-text">$</span>
                                                                </div>
                                                                <input type="text" className="form-control"
                                                                       name="price" value={this.state.product.price}
                                                                       onChange={(e) => this.handleChange(e)}/>
                                                            </div>

                                                        </div>
                                                        <div className="col-md-6 mb-3">
                                                            <label>status <span>*</span></label>
                                                            <select className="form-control" name="status"
                                                                    onChange={(e) => this.handleChange(e)}
                                                                    value={this.state.product.status}>
                                                                <option value=''>Choose Status</option>
                                                                {this.state.status.map(function (status, i) {
                                                                    return <option
                                                                        value={status.value} key={i}>
                                                                        {status.name}
                                                                    </option>;
                                                                })}
                                                            </select>
                                                        </div>

                                                        <div className="col-md-6 mb-3">
                                                            <label>Published <span>*</span></label>
                                                            <select className="form-control" name="is_published"
                                                                    onChange={(e) => this.handleChange(e)}
                                                                    value={this.state.product.is_published}>
                                                                <option value=''>Choose Status</option>
                                                                {this.state.published.map(function (status, i) {
                                                                    return <option
                                                                        value={status.value} key={i}>
                                                                        {status.name}
                                                                    </option>;
                                                                })}
                                                            </select>
                                                        </div>
                                                        <div className="col-md-12">
                                                            <button type="button"
                                                                    className="btn btn-outline-dark font-14 pull-right"
                                                                    onClick={() => this.save_product(this.props.match.params.id, this.state.product)}>
                                                                {(this.state.product.id === '') ? "Create" : "Update"}
                                                            </button>
                                                        </div>

                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>

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
    return ({
        error: state.error.error,
        getSavedProductProps: state.product.save_product,
        getProductById: state.product.fetch_product_by_id
    })
}

/**
 * mapDispatchToProp
 * @param  dispatch
 * @return dispatches
 */
function mapDispatchToProp(dispatch) {
    return ({
        fetchProductById: (shop_id, product_id) => {
            dispatch(_fetchProductById(shop_id, product_id));
        },
        saveProduct: (shop_id, params) => {
            dispatch(_saveProduct(shop_id, params));
        }

    })
}

export default connect(mapStateToProp, mapDispatchToProp)(CreateOrUpdateProduct);
