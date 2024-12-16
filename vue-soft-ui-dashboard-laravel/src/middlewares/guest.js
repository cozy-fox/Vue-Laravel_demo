export default function guest({ next }) {
  // if (store.getters["auth/loggedIn"]) {
  //   return next({
  //     name: "Profile",
  //   });
  // }
  next();
}
