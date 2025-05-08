// Simple Netlify Function to handle API requests
exports.handler = async function(event, context) {
  return {
    statusCode: 200,
    body: JSON.stringify({
      message: "Yohannes Finance API - This is a static website hosting of a Laravel application. API functionality is limited.",
      status: "online",
      time: new Date().toISOString()
    })
  };
}; 