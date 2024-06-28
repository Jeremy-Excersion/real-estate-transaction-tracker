export const formatPrice = (input) => {
    const num = parseFloat(input);
    const fixedNum = num.toFixed(2);
    let [integerPart, decimalPart] = fixedNum.split(".");
  
    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  
    return `${integerPart}.${decimalPart}`;
  };