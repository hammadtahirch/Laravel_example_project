import React, {Component} from 'react';
import {connect} from 'react-redux';
import moment from 'moment';
import {DatetimePickerTrigger} from 'rc-datetime-picker';

import 'react-toastify/dist/ReactToastify.css';
import {getSession} from "../../../../store/helper/helper";
import {ToastContainer, toast} from 'react-toastify';
import {
    _fetchProductById,

} from "../../../../store/action/action-product";
import ValidationErrors from "../../sub_components/ValidationErrors";
const queryString = require('query-string');

class CreateUpdateProductVariance extends Component {

    /**
     * constructor
     * @param props
     */
    constructor(props) {
        super(props);
        this.state = {};
    }

    /**
     * componentDidMount [react default life cycle functions]
     */
    componentDidMount() {
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
                        <h5>Product Variance</h5>
                        <hr/>
                        <form>
                            <div className="row ">
                                <div className="col-md-12 mb-3">
                                    <label>Title<span>*</span></label>
                                    <input type="text" className="form-control"
                                           name="title"/>
                                </div>
                                <div className="col-md-12 mb-3">
                                    <label>Description<span>*</span></label>
                                    <input type="text" className="form-control"
                                           name="description"/>
                                </div>
                                <div className="col-md-6 mb-3">
                                    <label> Maximum Permitted<span>*</span></label>
                                    <input type="text" className="form-control"
                                           name="city"/>
                                </div>
                                <div className="col-md-6 mb-3">
                                    <label>Minimum Permitted <span>*</span></label>
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

export default connect(mapStateToProp, mapDispatchToProp)(CreateUpdateProductVariance);
