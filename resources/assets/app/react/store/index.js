import rootReducer from './reducer';
import { createStore, applyMiddleware } from 'redux';
import thunk from 'redux-thunk';

/**
 *
 * @type {Store<any, Action> & {dispatch: any}}
 */
const store = createStore(
    rootReducer,
    {},
    applyMiddleware(thunk)
);

export default store;