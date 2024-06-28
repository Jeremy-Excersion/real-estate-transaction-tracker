// CommissionCalculator.js

export default function calculateAgentCommission(sale) {
    let soldPrice = parseFloat(sale.sold_price);
    let flatFee = null;
  
    // strip out $ if it is present
    sale.fees.forEach((fee) => {
      fee.fee_amount.toString().replace(/[^0-9.]/g, "");
    });
  
    // Subtract pre-commission fees
    sale.fees.forEach((fee) => {
      if (fee.split_type === 1) {
        soldPrice -= parseFloat(fee.fee_amount);
      }
      if (fee.split_type === 2) {
        soldPrice += parseFloat(fee.fee_amount);
      }
      if (fee.split_type === 8) {
        flatFee = parseFloat(fee.fee_amount);
      }
    });
  
    // Calculate total commission, if flatFee is set, use it, otherwise use percentage
    //   let totalCommission = (soldPrice * sale.percentage) / 100;
    let totalCommission = flatFee ? flatFee : (soldPrice * sale.percentage) / 100;
  
    // Subtract pre-team fees
    sale.fees.forEach((fee) => {
      if (fee.split_type === 3) {
        totalCommission -= parseFloat(fee.fee_amount);
      }
    });
  
    // Subtract referral fees
    sale.fees.forEach((fee) => {
      if (fee.split_type === 7) {
        totalCommission -= (totalCommission * parseFloat(fee.fee_amount)) / 100;
      }
    });
  
    // Add pre-team additions
    sale.fees.forEach((fee) => {
      if (fee.split_type === 4) {
        totalCommission += parseFloat(fee.fee_amount);
      }
    });
  
    // Calculate agent commission
    let agentCommission = (totalCommission * sale.agent_split_percentage) / 100;
  
    // Subtract post-team fees
    sale.fees.forEach((fee) => {
      if (fee.split_type === 5) {
        agentCommission -= parseFloat(fee.fee_amount);
      }
    });
  
    // Add post-team additions
    sale.fees.forEach((fee) => {
      if (fee.split_type === 6) {
        agentCommission += parseFloat(fee.fee_amount);
      }
    });
  
    // Return agent commission rounded up to two decimal places
    return parseFloat(Math.ceil(agentCommission * 100) / 100).toFixed(2);
  }