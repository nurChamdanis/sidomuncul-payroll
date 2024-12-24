function FormSubmitter(url, formData, btnSubmitId = 'btn_submit') {
    this.formData = formData;
    this.url = `${SITE_URL}${url}` || SITE_URL; // Default URL if not provided
    this.successCallback = null;
    this.beforeSendCallback = null;
    this.errorCallback = null;
    this.btnSubmitId = btnSubmitId;

    // Call beforeSend callback if provided
    if (typeof beforeSendCallback === 'function') {
        beforeSendCallback();
    }

    // Call submit method upon instantiation
    this.submit();
}

FormSubmitter.prototype.beforeSend = function(callback) {
    this.beforeSendCallback = callback;
    return this;
};

FormSubmitter.prototype.success = function(callback) {
    this.successCallback = callback;
    return this;
};

FormSubmitter.prototype.error = function(callback) {
    this.errorCallback = callback;
    return this;
};

FormSubmitter.prototype.submit = function(method) {
    var self = this;

    try {
        toggleSubmit('disabled'); // Disable submit button before sending

        var xhr = new XMLHttpRequest();
        xhr.open(method || 'POST', this.url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                toggleSubmit('enabled'); // Enable submit button after receiving response

                if (xhr.status >= 200 && xhr.status < 300) {
                    var data = JSON.parse(xhr.responseText);
                    self.handleSuccess(data);
                    // console.log('Completed');
                } else {
                    var error = new Error(`Network response was not ok`);
                    var errorMessage = xhr.responseText ?? '';
                    errorResult = JSON.parse(xhr.responseText);
                    if(errorResult.code){
                        if(errorResult.code >= 400){
                            error = new Error(`${errorResult.message}`);
                        }
                    }

                    self.handleError(error);
                    // console.error('Failed:', error);
                }
            }
        };
        xhr.onerror = function(error) {
            toggleSubmit('enabled'); // Enable submit button if error occurs
            self.handleError(error);
            // console.error('Failed:', error);
        };
        if (method && method.toUpperCase() === 'GET') {
            xhr.send();
        } else {
            xhr.send(this.formData);
        }
    } catch (error) {
        toggleSubmit('enabled'); // Enable submit button if error occurs
        this.handleError(error);
        // console.error('Failed:', error);
    }
};

FormSubmitter.prototype.handleSuccess = function(data) {
    if (typeof this.successCallback === 'function') {
        this.successCallback(data);
    }
    // You can chain more actions here if needed
};

FormSubmitter.prototype.handleError = function(error) {
    if (typeof this.errorCallback === 'function') {
        this.errorCallback(error);
    }
    // You can chain more error handling here if needed
};
