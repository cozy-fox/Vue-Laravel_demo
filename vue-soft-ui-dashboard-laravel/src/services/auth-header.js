export default function authHeader() {
  let user = JSON.parse(localStorage.getItem("userF"));
  console.log('user',user)
  if (user) {
    return {
      Authorization: "Bearer " + user,
      "Content-Type": "application/vnd.api+json",
    };
  } else {
    return {};
  }
}
