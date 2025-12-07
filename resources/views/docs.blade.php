<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400&display=swap"
        rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/doc/css/bootstrap.min.css">
    <link rel="stylesheet" href="/doc/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/doc/css/myCss.css">
    <title>MadarEx Integration Guide</title>
</head>

<body>
    <!-- navbar -->
    <nav class="navbar navbar-light bg-light fixed-top">
        <a class="navbar-brand" href="#">
            <img src="/doc/pic/logo.png" alt="">
        </a>
        {{-- <div class="nav-abs">
            <div class="dropdown">
                <button class="btn btn-danger dropdown-toggle " type="button" id="dropdownMenuButton"
                    data-toggle="dropdown" aria-expanded="false">
                    Dropdown
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else</a>
                </div>
            </div>

        </div> --}}
    </nav>
    <!-- sidebar  -->
    <div id="sidebar" class="active">
        <button id="sidetoggler" class="btn"> <span class="i-wr"></span></button>
        <div class="side-content">
            <div class="lay"></div>
            <ul class="nav flex-column" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <strong> RestFull apis</strong>
                <li class="nav-item">
                    <a class="nav-link active" href="#sec1">Get Token using credentails</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"  href="#sec2"  >Get Integration Factors</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"  href="#sec3" >Send Order To Madar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"  href="#sec4" >Cancel Order</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sec5" >Get Order History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sec6" >Get Bill as PDF</a>
                </li>
                {{-- <strong> side bar title</strong>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">nav link</a>
                </li> --}}


            </ul>

        </div>
    </div>
    <div id="page-content">
        <div class="tab-content" id="v-pills">
            <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1">
                <h2 class="title">Madar Express Api Documention</h2>
                <hr>
                <p>Please Follow These Steps To Connect with our system
                </p>
                {{-- <a href="https://www.google.com/">https://www.google.com/</a> --}}
                <div class="main-sec" id="sec1">

                    {{-- ************************************************* --}}
                    <h5 class="sub-title">Get Token using credentails </h5>
                    <a href="#">/api/get-token</a>
                    <h5 class="inner-title"> Method : </h5>
                    <p> Post</p>
                    <h5 class="inner-title"> Parameters : </h5>
                    <p>
                        email : required <br/>
                        password : required
                    </p>
                    <div class="bg-card v6">
                        On Success
                        <div>
                            <div class="highlight">
                                <pre class="chroma">
                                <code >

                                    {
                                        "data": {
                                            "company": {
                                                "id": int,
                                                "name": "string",
                                                "email": "string",
                                                "phone": "string",
                                                "adress_details": "string",
                                                "commercial_record": "string",
                                                "inside_price": int,
                                                "outside_price": int,
                                                "inside_delivery": int,
                                                "outside_delivery": int,
                                                "active": boolean,
                                                "image": "string",
                                                "rest_token": "string",
                                                "return_cost": float,
                                                "address": string,
                                                "latitude": string,
                                                "longitude": string
                                            }
                                        },
                                        "message": "success",
                                        "code": 200
                                    }
                                </code>
                                </pre>
                            </div>
                        </div>
                    </div>
                    <div class="bg-card v3">
                        On Failed
                        <div>
                            <div class="highlight">
                                <pre class="chroma">
                                <code >

                                    {
                                        "data": {},
                                        "errors": [
                                            " "
                                        ],
                                        "message": "authFailed",
                                        "code": 103
                                    }
                                </code>
                                </pre>
                            </div>
                        </div>
                    </div>
                    {{-- *********************************************** --}}
                </div>

                <div class="main-sec" id="sec2">

                    {{-- ************************************************* --}}
                    <h5 class="sub-title">Get Integration Factors </h5>
                    <a href="#">/api/get-info</a>
                    <h5 class="inner-title"> Method : </h5>
                    <p> Get</p>
                    <h5 class="inner-title"> Parameters : </h5>
                    <p>

                    </p>
                    <div class="bg-card v6">
                        On Success
                        <div>
                            <div class="highlight">
                                <pre class="chroma">
                                <code >

                                    {
                                        "data": {
                                            "cities": [
                                                {
                                                    "id": 3,
                                                    "name": "مكة",
                                                    "city_code": "Mecca",
                                                    "parent": 0,
                                                    "delivery_cost": 0
                                                }
                                            ],
                                            "payment_methods": [
                                                {
                                                    "id": 1,
                                                    "name": "الدفع عند الاستلام",
                                                },
                                                {
                                                    "id": 4,
                                                    "name": "مدفوع",
                                                },
                                                {
                                                    "id": 5,
                                                    "name": "التحويل البنكي",
                                                }
                                            ],
                                            "acceptable_payment_methods": [
                                                {
                                                    "id": 1,
                                                    "image": "18212716is_1_cdn1603237597.jpg",
                                                    "title": "تحويل على حساب بنكى",
                                                    "type": null,
                                                    "active": 1,
                                                },
                                                {
                                                    "id": 2,
                                                    "image": "58842996is_1_cdn1603237640.jpg",
                                                    "title": "تحصيل كاش",
                                                    "type": null,
                                                    "active": 1,
                                                }
                                            ]
                                        },
                                        "message": "allowed cities",
                                        "code": 200
                                    }
                                </code>
                                </pre>
                            </div>
                        </div>
                    </div>
                    <div class="bg-card v3">
                        On Failed
                        <div>
                            <div class="highlight">
                                <pre class="chroma">
                                <code >

                                    {
                                        "data": {},
                                        "errors": [
                                            " "
                                        ],
                                        "message": "notFound",
                                        "code": 404
                                    }
                                </code>
                                </pre>
                            </div>
                        </div>
                    </div>
                    {{-- *********************************************** --}}
                </div>

                <div class="main-sec" id="sec3">

                    {{-- ************************************************* --}}
                    <h5 class="sub-title">Send Order To Madar </h5>
                    <a href="#">/api/send-order</a>
                    <h5 class="inner-title"> Method : </h5>
                    <p> Post</p>
                    <h5 class="inner-title"> Parameters : </h5>
                    <p>
                        rest_token : required <br/>
                        recipient_name : required <br/>
                        recipient_phone : required <br/>
                        city_code : required <br/>
                        packages_number : required <br/>
                        refrence_no : required <br/>
                        adress_details : required <br/>
                        latitude : required <br/>
                        longitude : required <br/>
                        description : required <br/>
                        notes : optional
                    </p>
                    <div class="bg-card v6">
                        On Success
                        <div>
                            <div class="highlight">
                                <pre class="chroma">
                                <code >

                                    {
                                        "data": {
                                            "order": {
                                                "packages_number": "string",
                                                "price": "string",
                                                "notes": "string",
                                                "description": "string",
                                                "refrence_no": "string",
                                                "adress_details": "string",
                                                "latitude": "string",
                                                "longitude": "string",
                                                "recipent_name": "string",
                                                "phone": "string",
                                                "status": "string",
                                                "id": int,
                                                "serial": "string",
                                                "serial_no": int,
                                                "qr_code": "base64 image",
                                                "status_txt": "string",
                                                "status_image": "string url",
                                                "status_color": "string hex",
                                                "available_statuses": [],
                                            }
                                        },
                                        "message": "created",
                                        "code": 200
                                    }
                                </code>
                                </pre>
                            </div>
                        </div>
                    </div>
                    <div class="bg-card v3">
                        On Failed
                        <div>
                            <div class="highlight">
                                <pre class="chroma">
                                <code >

                                    {
                                        "data": {},
                                        "errors": [
                                            " "
                                        ],
                                        "message": "authFailed",
                                        "code": 103
                                    }
                                </code>
                                </pre>
                            </div>
                        </div>
                    </div>
                    {{-- *********************************************** --}}
                </div>

                <div class="main-sec" id="sec4">
                    {{-- ************************************************* --}}
                    <h5 class="sub-title">Cancel Order </h5>
                    <a href="#">/api/canel-order</a>
                    <h5 class="inner-title"> Method : </h5>
                    <p> Post</p>
                    <h5 class="inner-title"> Parameters : </h5>
                    <p>
                        rest_token : required <br/>
                        order_id : required
                    </p>
                    <div class="bg-card v6">
                        On Success
                        <div>
                            <div class="highlight">
                                <pre class="chroma">
                                <code >

                                    {
                                        "data": {
                                            "logs": [
                                                {
                                                    "status": "string",
                                                    "date": "string date",
                                                    "details": "string",
                                                    "image": "string url",
                                                    "color": "string hex"
                                                },
                                            ],
                                            "order": {
                                                "id": int,
                                                "recipent_name": "string",
                                                "status": "string",
                                                "description": "string",
                                                "weight": "string",
                                                "status_txt": "string",
                                                "status_image": "string url",
                                                "status_color": "string hex",
                                                "qr_code": "base64 image"
                                            }
                                        },
                                        "message": "order history",
                                        "code": 200
                                    }
                                </code>
                                </pre>
                            </div>
                        </div>
                    </div>
                    <div class="bg-card v3">
                        On Failed
                        <div>
                            <div class="highlight">
                                <pre class="chroma">
                                <code >

                                    {
                                        "data": [],
                                        "errors": [
                                            "not found"
                                        ],
                                        "message": "order not found",
                                        "code": 404
                                    }
                                </code>
                                </pre>
                            </div>
                        </div>
                    </div>
                    {{-- *********************************************** --}}

                </div>

                <div class="main-sec" id="sec5">
                    {{-- ************************************************* --}}
                    <h5 class="sub-title">Get Order History </h5>
                    <a href="#">/api/get-order-history</a>
                    <h5 class="inner-title"> Method : </h5>
                    <p> Post</p>
                    <h5 class="inner-title"> Parameters : </h5>
                    <p>
                        rest_token : required <br/>
                        order_id : required
                    </p>
                    <div class="bg-card v6">
                        On Success
                        <div>
                            <div class="highlight">
                                <pre class="chroma">
                                <code >

                                    {
                                        "data": {
                                            "logs": [
                                                {
                                                    "status": "string",
                                                    "date": "string date",
                                                    "details": "string",
                                                    "image": "string url",
                                                    "color": "string hex"
                                                },
                                            ],
                                            "order": {
                                                "id": int,
                                                "recipent_name": "string",
                                                "status": "string",
                                                "description": "string",
                                                "weight": "string",
                                                "status_txt": "string",
                                                "status_image": "string url",
                                                "status_color": "string hex",
                                                "qr_code": "base64 image"
                                            }
                                        },
                                        "message": "order history",
                                        "code": 200
                                    }
                                </code>
                                </pre>
                            </div>
                        </div>
                    </div>
                    <div class="bg-card v3">
                        On Failed
                        <div>
                            <div class="highlight">
                                <pre class="chroma">
                                <code >

                                    {
                                        "data": [],
                                        "errors": [
                                            "not found"
                                        ],
                                        "message": "order not found",
                                        "code": 404
                                    }
                                </code>
                                </pre>
                            </div>
                        </div>
                    </div>
                    {{-- *********************************************** --}}

                </div>

                <div class="main-sec" id="sec6">
                    {{-- ************************************************* --}}
                    <h5 class="sub-title">Get Bill as PDF </h5>
                    <a href="#">/api/get-bill</a>
                    <h5 class="inner-title"> Method : </h5>
                    <p> Post</p>
                    <h5 class="inner-title"> Parameters : </h5>
                    <p>
                        rest_token : required <br/>
                        order_id : required
                    </p>
                    <div class="bg-card v6">
                        On Success
                        <div>
                            <div class="highlight">
                                Pdf Stream
                            </div>
                        </div>
                    </div>
                    <div class="bg-card v3">
                        On Failed
                        <div>
                            <div class="highlight">
                                <pre class="chroma">
                                <code >

                                    {
                                        "data": [],
                                        "errors": [
                                            "not found"
                                        ],
                                        "message": "order not found",
                                        "code": 404
                                    }
                                </code>
                                </pre>
                            </div>
                        </div>
                    </div>
                    {{-- *********************************************** --}}

                </div>

            </div>
            <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="v-pills-profile-tab">...</div>
            <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="v-pills-messages-tab">...</div>
        </div>



    </div>




    <!-- Optional JavaScript -->

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="/doc/js/jquery-3.4.1.min.js"></script>
    <script src="/doc/js/popper.min.js"></script>
    <script src="/doc/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace()
    </script>
    <script src="/doc/js/myJquery.js"></script>
    <script>
        function createCopyButton(highlightDiv) {
            const button = document.createElement("button");
            button.className = "copy-code-button";
            button.type = "button";
            button.innerText = "Copy";
            button.addEventListener("click", () =>
                copyCodeToClipboard(button, highlightDiv)
            );
            addCopyButtonToDom(button, highlightDiv);
        }

        async function copyCodeToClipboard(button, highlightDiv) {
            const codeToCopy = highlightDiv.querySelector(":last-child > .chroma > code")
                .innerText;
            try {
                result = await navigator.permissions.query({
                    name: "clipboard-write"
                });
                if (result.state == "granted" || result.state == "prompt") {
                    await navigator.clipboard.writeText(codeToCopy);
                } else {
                    copyCodeBlockExecCommand(codeToCopy, highlightDiv);
                }
            } catch (_) {
                copyCodeBlockExecCommand(codeToCopy, highlightDiv);
            } finally {
                codeWasCopied(button);
            }
        }

        function copyCodeBlockExecCommand(codeToCopy, highlightDiv) {
            const textArea = document.createElement("textArea");
            textArea.contentEditable = "true";
            textArea.readOnly = "false";
            textArea.className = "copyable-text-area";
            textArea.value = codeToCopy;
            highlightDiv.insertBefore(textArea, highlightDiv.firstChild);
            const range = document.createRange();
            range.selectNodeContents(textArea);
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
            textArea.setSelectionRange(0, 999999);
            document.execCommand("copy");
            highlightDiv.removeChild(textArea);
        }

        function codeWasCopied(button) {
            button.blur();
            button.innerText = "Copied!";
            setTimeout(function () {
                button.innerText = "Copy";
            }, 2000);
        }

        function addCopyButtonToDom(button, highlightDiv) {
            highlightDiv.insertBefore(button, highlightDiv.firstChild);
            const wrapper = document.createElement("div");
            wrapper.className = "highlight-wrapper";
            highlightDiv.parentNode.insertBefore(wrapper, highlightDiv);
            wrapper.appendChild(highlightDiv);
        }

        document
            .querySelectorAll(".highlight")
            .forEach((highlightDiv) => createCopyButton(highlightDiv));
    </script>
</body>

</html>
