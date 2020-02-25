import {
  FETCH_ENROLL_LIST,
  FETCH_MONEY_BALANCE,
  FETCH_MONEY_BILL,
  FETCH_MONEY_DEBIT,
} from './mutation-type'

const mutations = {
  // 改变loading状态
  [FETCH_ENROLL_LIST](state, data) {
    state.enrollList = [ ...data ];
  },
  // 改变loading状态
  [FETCH_MONEY_BALANCE](state, data) {
    state.moneyBalance = { balance : data };
  },
  // 改变loading状态
  [FETCH_MONEY_BILL](state, data) {
    state.moneyBill = { ...data };
  },
  // 改变loading状态
  [FETCH_MONEY_DEBIT](state, data) {
    state.moneyDebit = { ...data };
  },
};
export default mutations;
