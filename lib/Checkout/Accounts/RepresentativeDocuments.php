<?php

namespace Checkout\Accounts;

/**
 * Verification documents for an individual representative, sent as
 * company.representatives[].documents (Accounts API v3.0).
 *
 * The API validates this object strictly: a key it does not recognise is rejected, not ignored.
 * These four are the only keys it accepts, and which of them apply depends on the onboarding
 * variant:
 * - EEA Sole Trader - Full (3.0): identity_verification, proof_of_residential_address and
 *   proof_of_registration, all three required.
 * - GB and US Sole Trader - Full (3.0): identity_verification, required.
 * - EEA, GB and US Company - Full (3.0): identity_verification and certified_authorised_signatory,
 *   both optional.
 *
 * Company-level documents such as bank_verification belong on the top-level request documents
 * (OnboardSubEntityDocuments), not here.
 */
class RepresentativeDocuments
{
    /**
     * The document to use to confirm the individual's identity.
     * [Optional] (required for the sole trader full variants)
     *
     * @var Document
     */
    public $identity_verification;

    /**
     * Certified authorised signatory document. Required when the legal representative or other
     * role owner is not registered on the certificate of incorporation.
     * [Optional] (company full variants only)
     *
     * @var CertifiedAuthorisedSignatory
     */
    public $certified_authorised_signatory;

    /**
     * Proof of residential address of the representative.
     * [Optional] (required for the EEA Sole Trader - Full (3.0) variant, and only valid there)
     *
     * @var ProofOfResidentialAddress
     */
    public $proof_of_residential_address;

    /**
     * Proof of the sole trader's registration, for example an extract from a trade register.
     * [Optional] (required for the EEA Sole Trader - Full (3.0) variant, and only valid there)
     *
     * @var ProofOfRegistration
     */
    public $proof_of_registration;
}
