<div class="dashboard-content-wrap">
    <div class="dashboard-profile-content">
        <div class="single-content">
            <h5>Basic Info</h5>
            <div class="text-center author-area">
                <div class="author-content">
                    <h4>
                        {{ auth('dealer')->user()->name }}
                    </h4>
                    {{-- <span>art Teacher</span> --}}
                </div>
            </div>
            {{-- <form>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-inner mb-30">
                            <label>Name *</label>
                            <input type="text" placeholder="Mr. Harry">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-inner mb-30">
                            <label>Date Of Birth </label>
                            <input type="text" placeholder="27/08/2024">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-inner mb-30">
                            <label>Email *</label>
                            <input type="email" placeholder="info@example.com">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-inner mb-30">
                            <label>Contact Number *</label>
                            <input id="phone" type="tel" name="phone" placeholder="0276768">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-inner mb-30">
                            <label>Your Address*</label>
                            <input type="text" placeholder="Mirpur DOHS, Dhaka">
                        </div>
                    </div>
                    <div class="col-md-6 mb-30">
                        <div class="form-inner">
                            <label>Country*</label>
                            <select>
                                <option>United Kingdom</option>
                                <option>Bangladesh</option>
                                <option>United State</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form> --}}
        </div>
        {{-- <div class="single-content style-2">
            <h5>Personal Address</h5>
            <form>
                <div class="row">
                    <div class="col-md-6 mb-30">
                        <div class="form-inner">
                            <label>City * </label>
                            <select>
                                <option>Dhaka</option>
                                <option>Rangpur</option>
                                <option>Nilphamari</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-30">
                        <div class="form-inner">
                            <label>State</label>
                            <select>
                                <option>Dhaka</option>
                                <option>Gazipur</option>
                                <option>Narayongonj</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-inner mb-30">
                            <label>Zip *</label>
                            <input type="text" placeholder="50000">
                        </div>
                    </div>
                    <div class="col-md-6 mb-30">
                        <div class="form-inner">
                            <label>Country *</label>
                            <select>
                                <option>United Kingdom</option>
                                <option>Bangladesh</option>
                                <option>United State</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-inner mb-30">
                            <label>Full Address *</label>
                            <input type="text" placeholder="Dhaka, Bangladesh">
                        </div>
                    </div>
                </div>
            </form>
        </div> --}}
        <livewire:frontend.dealer.dashboard.update-password-form />
    </div>
</div>
