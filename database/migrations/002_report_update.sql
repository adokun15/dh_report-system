ALTER TABLE reports
DROP COLUMN duplicates_record,
DROP COLUMN success_record,
MODIFY COLUMN system_id VARCHAR(255) NOT NULL,
MODIFY COLUMN enrolment_officer_phone VARCHAR(15) NOT NULL,
ADD COLUMN before_duplicates_record INT NOT NULL DEFAULT 0 CHECK (before_duplicates_record >= 0),
ADD COLUMN after_duplicates_record INT NOT NULL DEFAULT 0 CHECK (after_duplicates_record >= 0),
ADD COLUMN before_success_record INT NOT NULL DEFAULT 0 CHECK (before_success_record >= 0),
ADD COLUMN after_success_record INT NOT NULL DEFAULT 0 CHECK (after_success_record >= 0),
ADD COLUMN adult_record INT NOT NULL DEFAULT 0 CHECK (adult_record >= 0),
ADD COLUMN child_record INT NOT NULL DEFAULT 0 CHECK (child_record >= 0),
ADD COLUMN recap_record INT NOT NULL DEFAULT 0 CHECK (recap_record >= 0),
ADD COLUMN online_record INT NOT NULL DEFAULT 0 CHECK (online_record >= 0),
ADD COLUMN child_to_adult_record INT NOT NULL DEFAULT 0 CHECK (child_to_adult_record >= 0);
