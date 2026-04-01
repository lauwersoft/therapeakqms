# AI Model Design

Documentation that describes the design of AI model training and inference.

Documentation is required for medical devices that use AI models in their software to describe the design of the AI model's training and inference.

Model training and inference are fundamentally different phases of AI model development and documenting both aspects is required.

Distinct quality controls and validation strategies govern these phases, and their separation in the technical documentation allows for a more precise assessment of safety, effectiveness, and robustness throughout the device lifecycle.

The documentation must demonstrate that training data is representative and clinically relevant, that appropriate evaluation methodologies have been applied, and that inference procedures ensure consistent, safe operation in the intended use environment.

## Minimum Requirements

When submitting your technical documentation, the following are the minimum requirements for this topic:

- **AI model training documentation**: The documentation must describe the model architecture with justification, training data including representativeness and clinical relevance, data preprocessing, training procedure including loss function and hyperparameters, and model evaluation methodology including data-splitting strategy and validation results.
- **AI model inference documentation**: The documentation must describe the trained model(s) used for inference, model inputs including preprocessing and validation, model outputs including post-processing and uncertainty assessment, and hardware requirements for running inference.
- **Training data quality and bias management**: The documentation must identify data sources, describe labelling procedures, define inclusion/exclusion criteria, explain how data imbalance and missing data are managed, and identify and manage potential data biases and risks.

## Common Pitfalls

The following are common queries that are raised by Scarlet during assessment:

> **Warning:**
>
> - **Insufficient training data documentation**: Documentation that lacks justification for training data representativeness and clinical relevance, fails to identify data sources, or does not adequately describe how data imbalance, missing data, or potential biases are managed.
> - **Incomplete model evaluation methodology**: Documentation that does not describe the data-splitting strategy, validation approach, or provide summary results on training and validation sets, making it difficult to assess model performance and generalisation.
> - **Inadequate training procedure documentation**: Documentation that lacks description of the loss function, optimisation algorithm, key hyperparameters with rationale, or techniques used to prevent overfitting, making it difficult to assess training rigour and reproducibility.
> - **Missing or incomplete inference documentation**: Documentation that fails to describe model inputs, preprocessing steps, input validation methods, output validation, post-processing steps, or uncertainty assessment methods, making it difficult to assess inference safety and consistency.
> - **Inconsistency between training and inference**: Documentation where preprocessing steps applied during inference do not align with those used during training, or where model inputs/outputs are not clearly defined and consistent across both phases.

## Desired Submission Format

Scarlet specifies desired submission formats for certain technical documentation to facilitate easier assessment. It is recommended that AI model design documentation is provided as a standard document format, such as *.DOCX* or *.PDF*.

## Deep Dive

### Expected Content: AI Model Training - Model Architecture

Include:

- A description of the chosen model architecture and a justification for this decision
- A description of the model's primary hyperparameters
- A description of the model inputs and outputs

### Expected Content: AI Model Training - Training Data

Include:

- A description of the training data used
- A justification for the training data's representativeness and clinical relevance
- Identification of the data sources
- A description of the labelling procedure
- Definitions of any inclusion/exclusion criteria
- A description of how data imbalance is managed
- Identification and management of potential data biases and risks
- A description of how missing data is managed

### Expected Content: AI Model Training - Data Preprocessing

Include:

- A description of any preprocessing that is performed on the training data
- A description of any feature engineering that is performed on the training data

### Expected Content: AI Model Training - Training Procedure

Include:

- A description of the loss function used to train the model, including justification for its selection in the context of the prediction task
- A description of any optimisation algorithm used during training, including any learning rate schedules or decay strategies applied
- A specification of key training hyperparameters such as batch size, number of epochs, learning rate, weight decay, and early stopping criteria, with rationale for their chosen values
- A description of techniques used to prevent or mitigate overfitting, such as regularisation, dropout, data augmentation, early stopping, or ensembling, with justification based on model behaviour during training and validation

### Expected Content: AI Model Training - Model Evaluation Methodology

Include:

- A description of the chosen data-splitting strategy and a justification for this decision
- A description of the chosen validation approach (e.g. cross-validation or temporal splits)
- A summary of the results on training and validation sets

### Expected Content: AI Model Inference - Trained Model(s)

Include:

- Reference to the specific trained model(s) used for inference

### Expected Content: AI Model Inference - Combination of Models

If applicable, include:

- A description of any ensemble methods or multi-model strategies used during inference
- An explanation of the logic, weighting, or decision rules used to combine outputs from multiple models (e.g., majority voting, averaging, stacking)
- A justification for combining models, including expected robustness, accuracy, or generalisation benefits

### Expected Content: AI Model Inference - Model Inputs

Include:

- A detailed description of the input data required by the model(s), including modality (e.g., structured, image, text), format, and units
- A description of all preprocessing steps applied to input data before inference (e.g., normalisation, encoding, tokenisation, resizing). Note: This should align with the preprocessing used during model training to ensure consistency
- A description of any input validation or verification methods used to ensure data quality and format compliance before inference (e.g., schema checks, range checks, type enforcement)
- A description of how missing input data is handled at inference time (e.g., imputation, default values, rejection of inference request), with rationale for the chosen approach

### Expected Content: AI Model Inference - Model Output

Include:

- A description of the checks or safeguards used to validate model outputs, such as range checks, consistency checks, or constraint enforcement
- A description of any post-processing steps applied to raw model outputs to convert them into actionable or interpretable clinical outputs (e.g., thresholds for classification, mapping to clinical decision categories, or score banding)
- A description of any methods used to assess uncertainty or confidence in model predictions (e.g., confidence intervals, probability thresholds, entropy measures)
- A description of any model calibration techniques applied (e.g., Platt scaling, isotonic regression) to ensure alignment between predicted probabilities and actual outcome likelihoods

### Expected Content: AI Model Inference - Hardware Requirements

Include:

- A description of the hardware environment required for running inference, including:
  - Minimum CPU, GPU, memory, and disk requirements
  - Runtime or deployment dependencies
  - Hardware accelerators, if any, and their compatibility constraints
