# Assertion Voter Bundle

## Usage

### Register bundle in kernel

    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...
                new Appsco\AssertionVoterBundle\AppscoAssertionVoterBundle(),
            );

            // ...
        }
    }

### Configure

    # app/config/config.yml

    appsco_assertion_voter:
        voter_record_provider: appsco.assertion.voter_record_provider.orm # Default Doctrine ORM voter record provider
        voter_factory: appsco.assertion.voter_factory.simple

### Use

    // Controller

    public function fooAction()
    {
        // Fetch assertion information from your service
        $info = $this->get('your_info_provider')->getInfo();

        // Resolve roles
        $roles = $this->get('appsco.assertion.role_resolver')->resolve($info);
    }

## Persistence layer integration

Bundle can be integrated with any persistence layer by implementing your own `VoterRecordProviderInterface` or by using
one already provided in the bundle.


### Doctrine Orm Voter Record Provider

1. (required) Instruct bundle to use it

    Set `voter_record_provider` in `app/config/config.yml` to `appsco.assertion.voter_record_provider.orm`

2. (optional) Change voter record entity class

    Set container parameter `appsco.assertion.voter_record_provider.orm.class` to your entity class.


### Doctrine Dbal Voter Record Provider

1. (required) Instruct bundle to use it

    Set `voter_record_provider` in `app/config/config.yml` to `appsco.assertion.voter_record_provider.dbal`

2. (optional) Customize table name and fields

    By default it will try to fetch voter records from `VoterRecord` table and read the following columns: issuer, attribute, value, role

    You can customize each of these settings by setting container parameters:

        Table name      : appsco.assertion.voter_record_provider.dbal.table_name
        Issuer column   : appsco.assertion.voter_record_provider.dbal.issuer_column
        Attribute column: appsco.assertion.voter_record_provider.dbal.attribute_column
        Value column    : appsco.assertion.voter_record_provider.dbal.value_column
        Role column     : appsco.assertion.voter_record_provider.dbal.role_column: role

### Using custom Voter Record Provider

If you're using other persistence layer you can easily integrate bundle with it.

1. (required) Create provider service

    Create your own service which implements `Appsco\AssertionVoterBundle\VoterRecordProvider\VoterRecordProviderInterface` and
    register it within container.

2. (required) Instruct bundle to use it

    Set `voter_record_provider` in `app/config/config.yml` to `your.assertion_voter.record_provider.service_key`

## Decision Makers

Sometimes, you might want to do more complex decision making, eg. disable editing roles if user has no ROLE_ENABLED
etc. To do this you need more than just a set of simple vote records.

You need to implement your own decision maker.
Make a service which implements `BWC\Component\AssertionVoter\DecisionMakerInterface` and tag it:

    my.custom.assertion.decision_maker:
        class: My\Custom\DecisionMakerServiceClass
        tags:
            - { name: appsco.assertion.decision_maker, alias: my_decision_maker }

And now you can run assertions against it:

    public function fooAction()
    {
        // Fetch assertion information from your service
        $info = $this->get('your_info_provider')->getInfo();

        // Resolve roles
        $roles = $this->get('appsco.assertion.role_resolver')->resolve($info, 'my_decision_maker');
    }

> Note second parameter in RoleResolver::resolve method. It must match alias from `appsco.assertion.decision_maker` class.
