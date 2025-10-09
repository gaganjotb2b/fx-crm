
// id proof--------------
file_upload(
    "/user/user-admin/verify-form", //<--request url for proccessing
    false, //<---auto process true or false
    ".id-proof-dropzone",  //<---dropzones selectore
    "id-proof-form", //<---form id/selectore
    "#btn-save-id-proof", //<---submit button selectore
    "ID Verification" //<---Notification Title
);
// ib address proof--------------------------------------
file_upload(
    "/user/user-admin/verify-form", //<--request url for proccessing
    false, //<---auto process true or false
    ".address-proof-dropzone",  //<---dropzones selectore
    "address-proof-form", //<---form id/selectore
    "#btn-save-address-proof", //<---submit button selectore
    "Address Verification" //<---Notification title
);
